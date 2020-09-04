class App {
    constructor(){
        new IDB("seoul", ["papers", "inventory"], async db => {
            this.db = db;
            this.papers = await this.getPapers();
            this.cartList = [];
            this.searchTags = [];
            
            this.tags = this.papers.reduce((p,c) => [...p, ...c.hash_tags], []);
            this.searchModule = new HashModule("#search-module", this.tags);
            this.entryModule = new HashModule("#entry-module", this.tags);

            this.updateStore();
            this.updateCart();
            this.setEvents();
        });
    }

    get totalPoint(){
        return this.cartList.reduce((p, c) => p + c.totalPoint, 0);
    }
    
    get totalCount(){
        return this.cartList.reduce((p, c) => p + c.buyCount, 0);
    }

    async getPapers(){
        let papers = await this.db.getAll("papers");

        if(papers.length === 0){
            papers = await ( fetch("/json/papers.json").then(res => res.json()) );
            papers = papers.map(paper => ({
                ...paper,
                id: parseInt(paper.id),
                width_size: parseInt( paper.width_size.replace(/[^0-9]/g, "") ),
                height_size: parseInt( paper.height_size.replace(/[^0-9]/g, "") ),
                point: parseInt( paper.point.replace(/[^0-9]/g, "") ),
                image: "/images/papers/" + paper.image,
                hash_tags: paper.hash_tags.map(tag => tag.substr(1))
            }));

            papers.forEach(paper => {
                this.db.add("papers", paper);
            });
        }
        console.log(papers);

        return papers.map(paper => new Paper(paper));
    }

    updateStore(){
        let viewList = this.papers;

        if(this.searchTags.length > 0) {
            viewList = viewList.filter(item => this.searchTags.some(tag => item.hash_tags.includes(tag)));
        }

        $("#store").html("");
        viewList.forEach(item => {
            item.update();
            $("#store").append( item.$storeElem );
        });
    }

    updateCart(){
        let viewList = this.cartList;

        $("#cart").html("");
        viewList.forEach(item => {
            item.update();
            $("#cart").append( item.$cartElem );
        });

        $(".total-point").text( this.totalPoint.toLocaleString() );
        $("#totalPoint").val( this.totalPoint );
        $("#totalCount").val( this.totalCount );
        $("#cartList").val( this.cartList );
    }

    setEvents(){
        $("#image").on("change", e => {
            if(e.target.files.length === 0) return;
            let file = e.target.files[0];

            if(file.size > 1024 * 1024 * 5){
                alert("이미지는 5MB를 넘을 수 없습니다.");
                e.target.value = "";
            } else if(["jpg", "png", "gif"].includes(file.name.substr(-3).toLowerCase()) == false){
                alert("이미지 파일만 업로드 할 수 있습니다.");
                e.target.value = "";
            } else {
                let reader = new FileReader();
                reader.onload = () => {
                    $("#base64").val( reader.result );
                };
                reader.readAsDataURL(file);
            }
        });

        $("#add-form").on("submit", async e => {
            e.preventDefault();

            let inputs = Array.from( $("#add-form input[name]") )
                .reduce((p, c) => {
                    p[c.name] = c.value;
                    return p;
                }, {});
            
            inputs.width_size = parseInt(inputs.width_size);
            inputs.height_size = parseInt(inputs.height_size);
            inputs.point = parseInt(inputs.point);
            inputs.hash_tags = JSON.parse(inputs.hash_tags);
            
            inputs.id = await this.db.add("papers", inputs);

            this.papers.push( new Paper(inputs) );
            this.tags.push(...inputs.hash_tags);
            this.updateStore();

            $("#add-form").modal("hide");
        });

        $("#store").on("click", ".btn-add", e => {
            let paper = this.papers.find(paper => paper.id == e.currentTarget.dataset.id);
            paper.buyCount++;
            
            if( this.cartList.includes(paper) == false){
                this.cartList.push( paper );
            }

            this.updateStore();
            this.updateCart();
        });

        $("#cart").on("click", ".btn-remove", e => {
            let paper = this.cartList.find(item => item.id == e.currentTarget.dataset.id);
            paper.buyCount = 0;

            this.cartList = this.cartList.filter(item => item !== paper);
            
            this.updateStore();
            this.updateCart();
        });

        $("#cart").on("input", ".buy-count", e => {
            let value = parseInt( e.target.value );
            if(isNaN(value) || value < 1 || !value) value = 1;
            else if(value > 1000) value = 1000;

            let paper = this.cartList.find(item => item.id == e.currentTarget.dataset.id);
            paper.buyCount = value;

            this.updateStore();
            this.updateCart();

            e.target.focus();
        });

        $("#buy-form").on("submit", async e => {
            e.preventDefault();

            alert(`총 ${this.totalCount}개의 한지가 구매되었습니다.`);

            await Promise.all(this.cartList.map(async item => {
                let exist = await this.db.get("inventory", item.id);
                if(exist){
                    exist.hasCount += item.buyCount;
                    await this.db.put("inventory", exist);
                } else {
                    await this.db.add("inventory", {
                        id: item.id,
                        hasCount: item.buyCount,
                        paper_name: item.paper_name,
                        width_size: item.width_size,
                        height_size: item.height_size,
                        image: item.image,
                    });
                }
                item.buyCount = 0;
            }));

            this.cartList = [];
            this.updateStore();
            this.updateCart();
        });

        $(".btn-search").on("click", e => {
            this.searchTags = this.searchModule.tags;
            this.updateStore();
        });
    }
}

class Paper {
    constructor({id, paper_name, company_name, width_size, height_size, point, image, hash_tags}){
        this.id = id;
        this.paper_name = paper_name;
        this.company_name = company_name;
        this.width_size = width_size;
        this.height_size = height_size;
        this.point = point;
        this.image = image;
        this.hash_tags = hash_tags;
        this.buyCount = 0;

        this.$storeElem = $(`<div class="col-lg-3 mb-4">
                                <div class="bg-white border">
                                    <img src="${this.image}" alt="한지 이미지" class="fit-cover hx-200">
                                    <div class="p-3 border-top">
                                        <div class="fx-n3 text-muted">${this.company_name}</div>
                                        <div class="fx-3">${this.paper_name}</div>
                                        <div class="mt-1">
                                            <span class="fx-n2 text-muted">사이즈</span>
                                            <span class="fx-n1 ml-2">${this.width_size}px × ${this.height_size}px</span>
                                        </div>
                                        <div class="mt-1">
                                            <span class="fx-n2 text-muted">포인트</span>
                                            <span class="fx-n1 ml-2">${this.point}p</span>
                                        </div>
                                        <div class="mt-1 d-flex flex-wrap fx-n2 text-muted">
                                            ${ this.hash_tags.map(tag => `<span class="m-1">#${tag}</span>`).join('') }
                                        </div>
                                        <button class="btn-add btn-filled mt-3" data-id="${this.id}">구매하기</button>
                                    </div>
                                </div>
                            </div>`);
        this.$cartElem = $(`<div class="t-row">
                                <div class="cell-50">
                                    <div class="align-center">
                                        <img src="${this.image}" alt="한지 이미지" width="80" height="80">
                                        <div class="ml-4 text-left">
                                            <div>
                                                <span class="fx-3">${this.paper_name}</span>
                                                <span class="badge badge-danger">${this.point}p</span>
                                            </div>
                                            <div class="mt-1 text-muted fx-n1">${this.company_name}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="cell-20">
                                    <input type="number" class="buy-count" min="1" data-id="${this.id}">
                                </div>
                                <div class="cell-20">
                                    <span class="total fx-3">${this.totalPoint}</span>
                                    <span class="fx-n1 text-muted">p</span>
                                </div>
                                <div class="cell-10">
                                    <button class="btn-remove btn-filled" data-id="${this.id}">삭제</button>
                                </div>
                            </div>`);
    }
    
    get totalPoint(){
        return this.buyCount * this.point;
    }

    update(){
        this.$storeElem.find(".btn-add").text( this.buyCount > 0 ? `추가하기(${this.buyCount}개)` : "구매하기" );
        this.$cartElem.find(".total").text(this.totalPoint.toLocaleString());
        this.$cartElem.find(".buy-count").val( this.buyCount );
    }
}

$(function(){
    let app = new App();
});