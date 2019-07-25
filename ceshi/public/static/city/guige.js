
$.fn.guige = function(pName=21,cName=26){

    this.p = $(this).find('select[lay-filter=size]');

    this.c = $(this).find('select[lay-filter=color]');

    this.n = $('#num')[0];

    this.j = $('#total')[0];

    this.colorList = [];

    this.showP  = function(sizeList) {

        this.p.html('');

        is_pName = false;

        for (var i in sizeList) {

            if(pName==sizeList[i].id){

                is_pName = true;
                this.colorList = sizeList[i].colorList;
                this.p.append("<option selected value='"+sizeList[i].id+"'>"+sizeList[i].name+"</option>");
                price=parseInt(sizeList[i].price);
                this.j.value=parseInt(this.n.value)*price;
                // console.log(price);

            }else{
                this.p.append("<option value='"+sizeList[i].id+"'>"+sizeList[i].name+"</option>")
            }
        }
        if(!is_pName){
            this.colorList = sizeList[0].colorList;
        }

    };

    this.showC = function (colorList) {

        this.c.html('');

        for (var i in colorList) {
            if(cName==colorList[i].id){
                this.c.append("<option selected value='"+colorList[i].id+"'>"+colorList[i].name+"</option>")
            }else{
                this.c.append("<option value='"+colorList[i].id+"'>"+colorList[i].name+"</option>")
            }
        }

    };


    this.showP(sizeList);
    this.showC(this.colorList);

    form.render('select');

    form.on('select(size)', function(data){
        var pName = data.value;
        $(data.elem).parents(".x-guige").guige(pName);
    });
    return this;
};
