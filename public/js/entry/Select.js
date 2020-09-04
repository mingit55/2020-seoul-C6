class Select extends Tool {
    constructor(){
        super(...arguments);
        this.mouseTarget = null;
    }

    onmousedown(e){
        this.unselectAll();
        let target = this.getMouseTarget(e);
        
        if(target){
            target.active = true;
            this.mouseTarget = target;
            this.selected = target;

            this.firstXY = [target.x, target.y];
            this.downXY = this.getXY(e);
        } 
    }

    onmousemove(e){
        if(!this.mouseTarget) return;
        let target = this.selected;

        let [x, y] = this.getXY(e);
        let [fx, fy] = this.firstXY;
        let [dx, dy] = this.downXY;

        target.x = fx + x - dx;
        target.y = fy + y - dy;
    }

    onmouseup(){
        this.mouseTarget = null;
    }
}