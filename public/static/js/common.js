    // 菜单
    $(".angle_down").click(function(e){
        var e = e || window.event;
        if(e.stopPropagation){
            e.stopPropagation()
        }else{
            e.cancelBubble = true;
        };
        $(".second_menu").slideToggle();
        
    });

    // 选项卡
    $(".jifen_title>.con_title").click(function(){
        $(this).addClass("titleActive").siblings().removeClass("titleActive");
        var index = $(this).index();
        $(".oil_con>div").eq(index).removeClass("hide").siblings().addClass("hide");
    });

    // <!-- 起止时间 -->
if($(".iDate.date").length>0){
        $(".iDate.date").datetimepicker({
            locale:"zh-cn",
            format:"YYYY-MM-DD",
            dayViewHeaderFormat:"YYYY年 MMMM"
        });
    }



