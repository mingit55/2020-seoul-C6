class App {
    helps = [
        `선택 도구는 가장 기본적인 도구로써, 작업 영역 내의 한지를 선택할 수 있게 합니다. 마우스 클릭으로 한지를 활성화하여 이동시킬 수 있으며, 선택된 한지는 삭제 버튼으로 삭제시킬 수 있습니다.`,
        `회전 도구는 작업 영역 내의 한지를 회전할 수 있는 도구입니다. 마우스 더블 클릭으로 회전하고자 하는 한지를 선택하면, 좌우로 마우스를 끌어당겨 회전시킬 수 있습니다. 회전한 뒤에는 우 클릭의 콘텍스트 메뉴로 '확인'을 눌러 한지의 회전 상태를 작업 영역에 반영할 수 있습니다.`,
        `자르기 도구는 작업 영역 내의 한지를 자를 수 있는 도구입니다. 마우스 더블 클릭으로 자르고자 하는 한지를 선택하면 마우스를 움직임으로써 자르고자 하는 궤적을 그릴 수 있습니다. 궤적을 그린 뒤에는 우 클릭의 콘텍스트 메뉴로 '자르기'를 눌러 그려진 궤적에 따라 한지를 자를 수 있습니다.`,
        `붙이기 도구는 작업 영역 내의 한지들을 붙일 수 있는 도구입니다. 마우스 더블 클릭으로 붙이고자 하는 한지를 선택하면 처음 선택한 한지와 근접한 한지들을 선택할 수 있습니다. 붙일 한지를 모두 선택한 뒤에는 우 클릭의 콘텍스트 메뉴로 '붙이기'를 눌러 선택한 한지를 붙일 수 있습니다.`
    ];
    findList = [];
    focusIdx = null;

    constructor(){
        new IDB("seoul", ["inventory"], async db => {
            this.db = db;
            this.inventory = await this.getInventory();

            this.ws = new Workspace(this);

            let artworks = await( fetch("/json/craftworks.json").then(res => res.json()) );
            console.log(artworks);
            let tags = artworks.reduce((p, c) => [...p, ...c.hash_tags.map(tag => tag.substr(1))], []);
            this.entryModule = new HashModule("#entry-module", tags);

            this.setEvents();
        });
    }

    get focusItem(){
        return this.findList[this.focusIdx];
    }

    getInventory(){
        return this.db.getAll("inventory");
    }

    makeContextMenu(x, y, menus){
        $(".context-menu").remove();

        let $menus = $(`<div class="context-menu" style="left: ${x}px; top: ${y}px;"></div>`);

        menus.forEach(({name, onclick}) => {
            let $menu = $(`<div class="context-menu__item">${name}</div>`);
            $menu.on("mousedown", onclick);
            $menus.append( $menu );
        });

        $(document.body).append($menus);
    }


    setEvents(){
        $(window).on("mousedown", e => {
            $(".context-menu").remove();
        });

        $("[data-role].tool__item").on("click", e => {
            $(".tool__item").removeClass("active");
            let role = e.currentTarget.dataset.role;

            if(this.ws.tool){
                this.ws.tool.cancel && this.ws.tool.cancel();
                this.ws.tool.unselectAll();
            }

            if(this.ws.selected !== role){
                e.currentTarget.classList.add("active");
                this.ws.selected = role;
            } else {
                this.ws.selected = null;
            }
        });

        
        $(".btn-delete").on("mousedown", e => {
            if(this.ws.selected === 'select' && this.ws.tool.selected){
                this.ws.parts = this.ws.parts.filter(part => part !== this.ws.tool.selected);
                this.ws.tool.unselectAll();
            } else {
                alert("한지를 선택해 주세요.");
            }
        });


        $("[data-target='#add-modal']").on("click", e => {
            $("#add-modal .row").html( this.inventory.map(item => `<div class="col-lg-4">
                                                                    <div class="item bg-white border" data-id="${item.id}">
                                                                        <img src="${item.image}" alt="한지 이미지" class="hx-300 fit-cover">
                                                                        <div class="p-3 border-top">
                                                                            <div class="fx-3">${item.paper_name}</div>
                                                                            <div class="mt-2">
                                                                                <span class="fx-n2 text-muted">사이즈</span>
                                                                                <span class="fx-n1 ml-2">${item.width_size}px × ${item.height_size}px</span>
                                                                            </div>
                                                                            <div class="mt-2">
                                                                                <span class="fx-n2 text-muted">소지수량</span>
                                                                                <span class="fx-n1 ml-2">${item.hasCount}</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>`).join('') );
        });

        $("#add-modal").on("click", ".item", e => {
            let item = this.inventory.find(item => item.id == e.currentTarget.dataset.id);
            item.hasCount--;

            if(item.hasCount === 0){
                this.inventory = this.inventory.filter(it => it !== item);
                this.db.delete("inventory", item.id);
            } else {
                this.db.put("inventory", item);
            }

            this.ws.addPart( {imageURL: item.image, width: item.width_size, height: item.height_size} );

            $("#add-modal").modal("hide");
        });

        $(".btn-search").on("click", () => search());
        $(".help-search > input").on("keydown", e => {
            if(e.keyCode === 13){
                search(e);
            }
        });
        var search = e => {
            if($(".help-search > input").val().length === 0) return;
            let regex = new RegExp(
                $(".help-search > input").val().replace(/([.*?+^$\(\)\[\]\\\\\\/])/g, "\\$1"), "g"
            );

            this.helps.forEach((text, i) => {
                $(`.help-body > .tab`).eq(i).html(text.replace(regex, m1 => `<span>${m1}</span>`));
            });

            this.findList = Array.from( $(".help-body span") );
            if(this.findList.length === 0){
                this.focusIdx = null;
                $(".search-message").text(`일치하는 내용이 없습니다.`);
            } else {
                this.focusIdx = 0;

                $(".help-body span.active").removeClass("active");
                this.focusItem.classList.add("active");

                let target = this.focusItem.parentElement.dataset.target;
                $("[name='tabs']").removeAttr("checked");
                $(target).attr("checked", true);

                $(".search-message").text(`${this.findList.length}개 중 ${this.focusIdx + 1}번째`);
            }
        };

        $(".btn-prev").on("click", e => {
            if(this.focusIdx === null || this.findList.length === 0) return;

            this.focusIdx = this.focusIdx - 1 < 0 ? this.findList.length - 1 : this.focusIdx - 1;

            $(".help-body span.active").removeClass("active");
            this.focusItem.classList.add("active");

            let target = this.focusItem.parentElement.dataset.target;
            $("[name='tabs']").removeAttr("checked");
            $(target).attr("checked", true);

            $(".search-message").text(`${this.findList.length}개 중 ${this.focusIdx + 1}번째`);
        });

        $(".btn-next").on("click", e => {
            if(this.focusIdx === null || this.findList.length === 0) return;

            this.focusIdx = this.focusIdx + 1 >= this.findList.length ? 0 : this.focusIdx + 1;

            $(".help-body span.active").removeClass("active");
            this.focusItem.classList.add("active");

            let target = this.focusItem.parentElement.dataset.target;
            $("[name='tabs']").removeAttr("checked");
            $(target).attr("checked", true);

            $(".search-message").text(`${this.findList.length}개 중 ${this.focusIdx + 1}번째`);
        });
        
    }
}

$(function(){
    let app = new App();
});