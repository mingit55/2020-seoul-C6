class Workspace {
    constructor(app){
        this.app = app;

        this.canvas = document.querySelector(".workspace > canvas");
        this.ctx = this.canvas.getContext("2d");
        this.ctx.fillStyle = "#fff";
        
        this.sliced = document.createElement("canvas");
        this.sliced.width = this.canvas.width;
        this.sliced.height = this.canvas.height;
        this.sctx = this.sliced.getContext("2d");

        this.parts = [];
        this.selected = null;
        this.tools = {
            select: new Select(this),
            spin: new Spin(this),
            cut: new Cut(this),
            glue: new Glue(this)
        };

        this.render();
        this.setEvents();
    }
    
    get tool(){
        return this.tools[this.selected];
    }

    async addPart({imageURL, width, height}){
        let image = await new Promise(res => {
            let img = new Image();
            img.src = imageURL;
            img.onload = () => res(img);
        });

        let canvas = document.createElement("canvas");
        canvas.width = width;
        canvas.height = height;
        let ctx = canvas.getContext("2d");
        ctx.drawImage(image, 0, 0, width, height);
        
        let src = new Source( ctx.getImageData(0, 0, width, height) );
        this.parts.push( new Part( src ) );
    }

    render(){
        this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);

        this.parts.forEach(part => {
            part.update();
            this.ctx.drawImage(part.canvas, part.x, part.y);
            // this.ctx.strokeRect(part.x, part.y, part.src.width, part.src.height);
        });

        this.ctx.drawImage(this.sliced, 0, 0);
        
        requestAnimationFrame( () => this.render() );
    }

    setEvents(){
        $(".workspace").on("mousedown", e => {
            if(e.which === 1 && this.tool && this.tool.onmousedown){
                e.preventDefault();
                this.tool.onmousedown(e);
            }
        });
        $(".workspace").on("mousemove", e => {
            if(e.which === 1 && this.tool && this.tool.onmousemove){
                e.preventDefault();
                this.tool.onmousemove(e);
            }
        });
        $(".workspace").on("mouseup", e => {
            if(e.which === 1 && this.tool && this.tool.onmouseup){
                e.preventDefault();
                this.tool.onmouseup(e);
            }
        });
        $(".workspace").on("dblclick", e => {
            if(e.which === 1 && this.tool && this.tool.ondblclick){
                e.preventDefault();
                this.tool.ondblclick(e);
            }
        });
        $(window).on("contextmenu", e => {
            if(this.tool && this.tool.oncontextmenu){
                e.preventDefault();
                this.tool.oncontextmenu( menus => this.app.makeContextMenu(e.pageX, e.pageY, menus) );
            }
        });
    }
}