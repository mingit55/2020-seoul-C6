class Spin extends Tool {
    constructor(){
        super(...arguments);
    }

    ondblclick(e){
        let target = this.getMouseTarget(e);

        if(target && !this.selected){
            target.active = true;
            target.recalculate();
            
            this.selected = target;
            this.prevImage = target.src;
            this.prevSliced = this.createCanvas(target.sliced.width, target.sliced.height);
            let psctx = this.prevSliced.getContext("2d");
            psctx.drawImage(target.sliced, 0, 0);

            this.image = this.createCanvas(target.src.width, target.src.height);
            let ctx = this.image.getContext("2d");
            ctx.putImageData(target.src.imageData, 0, 0);
            
            this.sliced = this.createCanvas(target.sliced.width, target.sliced.height);
            let sctx = this.sliced.getContext("2d");
            sctx.drawImage(target.sliced, 0, 0);

            let spinSize = Math.sqrt( Math.pow( target.src.width, 2 ) + Math.pow( target.src.height, 2 ) );
            let imgX = (spinSize - target.src.width) / 2;
            let imgY = (spinSize - target.src.height) / 2;

            target.canvas.width = target.canvas.height = spinSize;
            target.sliced.width = target.sliced.height = spinSize;

            target.x -= imgX;
            target.y -= imgY;
            
            this.canvas = this.createCanvas( spinSize, spinSize );
            this.ctx = this.canvas.getContext("2d");


            this.ctx.drawImage( this.image, imgX, imgY );
            target.src = new Source( this.ctx.getImageData(0, 0, spinSize, spinSize) );
            
            target.sctx.clearRect(0, 0, spinSize, spinSize);
            target.sctx.drawImage( this.sliced, imgX, imgY );
        }
    }

    onmousedown(e){
        if(!this.selected) return;
        this.prevX = e.pageX;
    }

    onmousemove(e){
        if(!this.selected) return;
        let x = e.pageX;
        let angle = (this.prevX - x) * Math.PI / 180;
        this.prevX = x;

        let spinSize = this.canvas.width;
        let center = spinSize / 2;

        this.ctx.translate(center, center);
        this.ctx.rotate(angle);
        this.ctx.translate(-center, -center);
        
        let imgX = (spinSize - this.image.width) / 2;
        let imgY = (spinSize - this.image.height) / 2;
        console.log(spinSize, imgX, imgY);

        this.ctx.clearRect(0, 0, spinSize, spinSize);
        this.ctx.drawImage(this.image, imgX, imgY);
        this.selected.src = new Source( this.ctx.getImageData(0, 0, spinSize, spinSize) );

        this.ctx.clearRect(0, 0, spinSize, spinSize);
        this.ctx.drawImage(this.sliced, imgX, imgY);
        this.selected.sctx.putImageData( this.ctx.getImageData(0, 0, spinSize, spinSize), 0, 0 );
    }

    oncontextmenu(makeFunc){
        if(!this.selected) return;
        makeFunc([
            {name: "확인", onclick: this.accept},
            {name: "취소", onclick: this.cancel}
        ]);
    }

    accept = e => {
        if(!this.selected) return;
        
        this.selected.recalculate();
        this.unselectAll();
    }

    cancel = e => {
        if(!this.selected) return;    
        let target = this.selected;
        
        let imgX = (this.canvas.width - this.image.width) / 2;
        let imgY = (this.canvas.height - this.image.height) / 2;

        target.canvas.width = target.sliced.width = this.prevImage.width;
        target.canvas.height = target.sliced.height = this.prevImage.height;

        target.src = this.prevImage;
        target.x += imgX;
        target.y += imgY;

        target.sliced = this.prevSliced;
        target.sctx = this.prevSliced.getContext("2d");

        this.unselectAll();
    };
}