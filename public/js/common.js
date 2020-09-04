class IDB {
    constructor(dbname, stores = [], callback = () => {}){
        let req = indexedDB.open(dbname, 1);
        req.onupgradeneeded = () => {
            let db = req.result;
            stores.forEach(store => {
                db.createObjectStore(store, {keyPath: "id", autoIncrement: true});
            });
        };
        req.onsuccess = () => {
            this.db = req.result;
            callback(this);  
        };
    }

    objectStore(storeName){
        return this.db.transaction(storeName, "readwrite").objectStore(storeName);
    }
    
    getAll(storeName){
        return new Promise(res => {
            let os = this.objectStore(storeName);
            let req = os.getAll();
            req.onsuccess = () => res(req.result);
        });
    }

    get(storeName, id){
        return new Promise(res => {
            let os = this.objectStore(storeName);
            let req = os.get(id);
            req.onsuccess = () => res(req.result);
        });
    }

    delete(storeName, id){
        return new Promise(res => {
            let os = this.objectStore(storeName);
            let req = os.delete(id);
            req.onsuccess = () => res(req.result);
        });
    }

    add(storeName, data){
        return new Promise(res => {
            let os = this.objectStore(storeName);
            let req = os.add(data);
            req.onsuccess = () => res(req.result);
        });
    }

    put(storeName, data){
        return new Promise(res => {
            let os = this.objectStore(storeName);
            let req = os.put(data);
            req.onsuccess = () => res(req.result);
        });
    }
}


class HashModule {
    constructor(selector, list){
        this.$root = $(selector);
        this.hasList = list;
        this.showList = [];
        this.tags = [];
        this.focusIdx = null;

        this.init();
        this.setEvents();
    }

    init(){
        this.$root.html(`<input type="hidden" class="value" name="${this.$root.data('name')}" value="[]">
                            <div class="hash-module">
                                <div class="hash-module__input">
                                    #
                                    <input type="text" class="input">
                                    <div class="example">
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="error text-red fx-n2 mt-2"></div>`);
        this.$container = this.$root.find(".hash-module")
        this.$value = this.$root.find(".value");
        this.$input = this.$root.find(".input");
        this.$error = this.$root.find(".error");
        this.$list = this.$root.find(".example");
    }

    update(){
        this.$list.html("");
        this.showList.forEach((tag, i) => {
            this.$list.append( `<div class="example__item ${i === this.focusIdx ? 'active' : ''}" data-idx="${i}">#${tag}</div>` );
        });

        this.$container.find(".hash-module__item").remove();
        this.tags.forEach((tag, i) => {
            this.$container.append(`<div class="hash-module__item">#${tag}<span class="remove" data-idx="${i}">×</span></div>`);
        });

        this.$value.val( JSON.stringify(this.tags) );
    }


    addTag(tagname){
        if(tagname.length < 2 || 30 < tagname.length) return;
        else if( this.tags.includes( tagname ) ) {
            this.$error.text("이미 추가한 태그입니다.");
            return;
        } else if( this.tags.length >= 10 ) {
            this.$error.text("태그는 10개까지만 추가할 수 있습니다.");
            return;
        } else {
            this.tags.push( tagname );

            this.$input.val("");
            this.showList = [];
            this.focusIdx = null;
            
            this.update();
        }
    }

    setEvents(){
        this.$input.on("input", e => {
            e.target.value = e.target.value.replace(/[^a-zA-Z0-9ㄱ-ㅎㅏ-ㅣ가-힣_]/g, "");
            
            this.showList = [];
            this.focusIdx = null;
            this.$error.text("");

            if(e.target.value.length > 0){
                let regex = new RegExp(
                    "^" + e.target.value.replace(/([.+*?^$\(\)\[\]\\\\\\/])/g, "\\$1")
                );

                this.hasList.forEach(tag => {
                    if(regex.test(tag) && this.showList.includes(tag) == false){
                        this.showList.push(tag);
                    }
                });
            }

            this.update();
        });

        this.$input.on("keydown", e => {
            if(e.keyCode === 13 && this.focusIdx !== null){
                e.preventDefault();
                this.$input.val( this.showList[this.focusIdx] );
                this.showList = [];
                this.focusIdx = null;
            } else if( [9, 13, 32].includes(e.keyCode) ){
                e.preventDefault();
                this.addTag( this.$input.val() );
            } else if(e.keyCode === 38){
                e.preventDefault();
                this.focusIdx = this.focusIdx === null ? 0
                    : this.focusIdx - 1 < 0 ? this.showList.length - 1
                    : this.focusIdx - 1
            } else if(e.keyCode === 40){
                e.preventDefault();
                this.focusIdx = this.focusIdx === null ? 0
                    : this.focusIdx + 1 >= this.showList.length ? 0
                    : this.focusIdx + 1
            }

            this.update();
        });

        this.$list.on("click", ".example__item", e => {
            this.focusIdx = parseInt(e.currentTarget.dataset.idx);
            this.update();
            this.$input.focus();
        });

        this.$container.on("click", ".remove", e => {
            let idx = parseInt( e.currentTarget.dataset.idx );
            this.tags.splice(idx, 1);
            this.update();
        });
    }
}