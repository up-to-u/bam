"use strict";

(function() {
    home();
    about();
    credit();
    news();
    knowledge();
    feedback();
    contact();
    setMenu();
    sizeFont();
    setFooterLink();
    // setSizeCustomer();
    $(window).resize(function(){
        setMenu();
    });
    $('.checkbox_accept').click(function(){
        if($('.checkbox_accept').prop('checked')){
            $('.btn_credit_register').removeClass('disabled').attr('style', '');
        }else{
            $('.btn_credit_register').addClass('disabled').attr('style', 'border-color:#ccc; color:#ccc');
        }
    });
    $(".numberonly").keypress(function (event) {
        return event.charCode >= 48 && event.charCode <= 57;
    });
    $(".currency").keypress(function (event) {
        var selection = window.getSelection().toString();
        if ( selection !== '' ) {
            return;
        }
        if ( $.inArray( event.keyCode, [38,40,37,39] ) !== -1 ) {
            return;
        }
        var $this = $( this );
        var input = $this.val();
        var input = input.replace(/[\D\s\._\-]+/g, "");
        input = input ? parseInt( input, 10 ) : 0;
        $this.val( function() {
            return ( input === 0 ) ? "" : input.toLocaleString( "en-US" );
        });
    });
    $('.cardID').keypress(function(){
        var n = $(this).val();
        if( n.length==1 || n.length==6 || n.length==12 || n.length==15){
            $(this).val( n+'-' );
        }
    });
    $('.input-group input').focusin(function(){
        $(this).next('.input-group-addon').css({
            'border-color' : '#000'
        });
    });
    $('.input-group input').focusout(function(){
        $(this).next('.input-group-addon').css({
            'border-color' : '#ccc'
        });
    });
    $('.input_news').change(function(){
        var check = $(this).prop( "checked" );
        var btn = $(this).parents('form').find('.btn-contact');
        if(check){
            btn.removeClass('disabled');
        }else{
            btn.addClass('disabled');
        }
    });
    $('body').on("click", ".thumb", function(e){
        var tag = $('.thumb a');
        if(!tag.is(e.target) && tag.has(e.target).length === 0){
            var link = $(this).find('a').attr('href');
            window.location.href = link;
        }
    });
    $('footer .logo').click(function () {
        var link = $('header .navbar-brand').attr('href');
        window.location.href = link;;
    });
    $('header .lang-box .option .in').click(function(){
        $(this).parents('.lang-box').find('.option').hide();
        $(this).parents('.lang-box').find('.select').html( $(this).html() );
        $(this).parents('.lang-box').find('i').attr('class', 'fa fa-angle-down');
    });
    $('header .lang-box .select').click(function(){
        if( $(this).hasClass('open') ){
            $(this).removeClass('open');
            $(this).parents('.lang-box').find('.option').hide();
        }else{
            $(this).addClass('open');
            $(this).parents('.lang-box').find('.option').show();
            $(this).parents('.lang-box').find('i').attr('class', 'fa fa-angle-up');
        }
    });
    $('header .font-size .select').click(function(){
        $('#menu .navbar-nav .sub-menu').hide();
        if($('.font-size').hasClass('open')){
            $('.font-size').removeClass('open');
        }else{
            $('.font-size').addClass('open');
        }
    });
    $('.icon-actions .sc img:eq(0)').click(function(){
        var parent = $(this).parent();
        if( parent.hasClass('open') ){
            parent.removeClass('open');
        }else{
            parent.addClass('open');
        }
    });
    $('#share .print, .icon-actions img:eq(5)').click(function(){
        window.print();
    });
    $('#share .copy, .icon-actions img:eq(6)').click(function(){
        copyTextToClipboard(location.href);
    });
    $('#share .twitter, .icon-actions .sc img:eq(4)').click(function(){
        var title = $('meta[property="og:title"]').attr('content'); 
        var text = (title == undefined) ? 'SME Bank' : title;
        window.open('http://twitter.com/share?url='+location.href+'&text='+text, '_blank');
    });
    $('#share .google, .icon-actions .sc img:eq(3)').click(function(){
        window.open('https://plus.google.com/share?url='+location.href, '_blank');
    });
    $('#share .line, .icon-actions .sc img:eq(2)').click(function(){
        window.open('http://line.me/R/msg/text/?'+location.href, '_blank');
    });
    $('#share .facebook, .icon-actions .sc img:eq(1)').click(function(){
        window.open('http://www.facebook.com/sharer/sharer.php?u='+location.href, '_blank');
    });
    $('.upload input').change(function() {
        $(this).parents('.upload').find('label').html(this.files && this.files.length ? this.files[0].name.split('.')[0] : '');  
    });
    $('#totop').click(function(){
        $('html, body').animate({ scrollTop: 0 }, 'fast');
        $('#share').removeClass('open');
    });
    $('#share .head').click(function(){
        if( $(this).parents().hasClass('open') ){
            $(this).parents().removeClass('open');
        }else{
            $(this).parents().addClass('open');
        }
    });
    $('.topbar .box .label.has-sub').hover(function(){
        $('#menu .navbar-nav .sub-menu').hide();
        $(this).parents('.box').find('.sub-menu').fadeIn(100);
    }, function(){

    });
    $('.topbar .box .sub-menu').hover(function(){
        
    }, function(){
        $(this).fadeOut('fast');
    });
    $('.radio label input[type="radio"]').click(function(){
        var val = $(this).prop("checked");
        var name = $(this).attr('name');
        $('input[name="'+name+'"]').parents('label').removeClass('active');
        $(this).parents('label').addClass('active');
    });
    $('#tab .tab-head .item').click(function(){
        var index = $(this).index();
        $(this).parents('#tab').find('.tab-head .item .box').removeClass('active');
        $(this).find('.box').addClass('active');
        $(this).parents('#tab').find('.tab-content .item').hide().eq(index).fadeIn(200);
    });
    $('.media-popup-vdo').magnificPopup({
        type:'inline'
    });
    $('.media-popup').magnificPopup({
        delegate: 'a',
        type: 'image',
        gallery: {
            enabled: true
        }
    });
    $('#banner .item').click(function(){
        if($(this).find('a').length > 0){
            var link = $(this).find('a').attr('href');
            window.location.href = link;
        }
    });
    if( $('#accordion').length > 0 ){
        $('#accordion').find('.panel-heading .panel-title a').css({
            'padding-right' : '50px'
        });
    }

    document.addEventListener('copy', addLink);
    if($('#slide_service .item').length > 1){
        var index = $('#slide_service .item a.active').parent().index();
        $('#slide_service').owlCarousel({
            nav: true,
            navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
            loop: false,
            dots: false,
            startPosition: index,
            responsive : {
                0 : {
                    items : 1,
                    margin: 0,
                },
                768 : {
                    items: 3,
                    margin: 20,
                },
                920 : {
                    items: 4,
                    margin: 40,
                }
            }
        });
        if($('#slide_service .item').length <= 4 && $(window).width() >= 920){
            $('#slide_service .owl-controls').hide();
        }
        $('#slide_service').on('changed.owl.carousel', function() {
            setTimeout(function(){
                var url =  $('#slide_service .owl-item.active .item a').attr('href');
                window.location.href = url;
            },500);
        });
    }
    function addLink() {
        console.log('copy text');
        var selection = window.getSelection();
        var pagelink = " : อ่านต่อได้ที่ " + document.location.href;
        var copytext = selection + pagelink;
        
        var newdiv = document.createElement('div');
    
        newdiv.style.position = 'absolute';
        newdiv.style.left = '-99999px';
    
        document.body.appendChild(newdiv);
        newdiv.innerHTML = copytext;
        selection.selectAllChildren(newdiv);
    
        window.setTimeout(function () {
            document.body.removeChild(newdiv);
        }, 100);
    }
    function copyTextToClipboard(text) {
        var textArea = document.createElement("textarea");
        textArea.style.position = 'fixed';
        textArea.style.top = 0;
        textArea.style.left = 0;
        textArea.style.width = '2em';
        textArea.style.height = '2em';
        textArea.style.padding = 0;
      
        // Clean up any borders.
        textArea.style.border = 'none';
        textArea.style.outline = 'none';
        textArea.style.boxShadow = 'none';
      
        // Avoid flash of white box if rendered for any reason.
        textArea.style.background = 'transparent';
      
      
        textArea.value = text;
      
        document.body.appendChild(textArea);
      
        textArea.select();
      
        try {
          var successful = document.execCommand('copy');
          var msg = successful ? 'successful' : 'unsuccessful';
          console.log('Copying text command was ' + msg);
        } catch (err) {
          console.log('Oops, unable to copy');
        }
      
        document.body.removeChild(textArea);
    }
    function setMenu(){
        var width = $(window).width();
        $(window).scroll(function(){
            var scroll = $(window).scrollTop();
            if(scroll < 100){
                $('header').removeClass('scroll');
                $('#totop').hide();
                if(width <= 768 ) $('#share').hide();
            }else{
                $('header').addClass('scroll');
                $('#totop').fadeIn(200);
                if(width <= 768 ) $('#share').fadeIn(200);
            }
        });

        if(width <= 768){
            $('#menu .navbar-nav a').off("click").click(function(){
                if($(this).hasClass('has-sub')){
                    var parent =  $(this).parents('li');
                    if(parent.hasClass('show')){
                        $(this).parents('li').removeClass('show');
                    }else{
                        $(this).parents('li').addClass('show');
                    }
                }
            });
            $('footer .middle .control').off("click").click(function(){
                var active = $(this).hasClass('open');
                // $(this).find('.ct').stop().slideToggle();
                if(active){
                    $(this).find('.ct').slideUp(500);
                    $(this).removeClass('open');
                }else{
                    $(this).find('.ct').slideDown(500);
                    $(this).addClass('open');
                }
            });
            $('#wrapper').attr('style','');
        }else{
            $('#menu .navbar-nav a').hover(function(){
                $('.topbar .box .sub-menu').hide();
                if($(this).parent().parent().hasClass('navbar-nav')){
                    $('#menu .sub-menu').hide();
                    if($(this).hasClass('has-sub')){
                        $(this).parents('li').find('.sub-menu').show();
                    }
                }
            }, function(){
        
            });
            $('#menu .sub-menu').hover(function(){
                
            }, function(){
                $(this).fadeOut('fast');
            });
            setTimeout(function(){
                $('#wrapper').css({'padding-top' : $('nav').height()+'px' });
            }, 500);
        }
    }
    function contact(){
        $('#searchBranch button').click(function(){
            $('.contact-result').hide().fadeIn();
        });
        $('.selectpicker').selectpicker({
            dropupAuto: false
        });
    }
    function feedback(){
        $('.feedback-type .in').click(function(){
            var val = $(this).attr('data-type');
            $('.feedback-type .in').removeClass('active');
            $(this).addClass('active');
        });
    }
    function knowledge(){
        if($('.slide_knowledge .item').length > 1){
            $('.slide_knowledge').owlCarousel({
                items: 1,
                loop: false,
                dots: true,
                navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
                responsive : {
                    0 : {
                        nav: false,
                    },
                    1050 : {
                        nav: true,
                    }
                }
            });
        }

        // $('.detail .see_more').click(function(){
        //     $(this).parents('.detail').find('.ct').slideDown();
        //     $(this).parents('.detail').find('.see_more').hide();
        //     $(this).parents('.detail').find('.hide_article').show();
        // });

        $('.detail .hide_article').click(function(){
            $(this).parents('.detail').find('.ct').slideUp();
            $(this).parents('.detail').find('.see_more').show();
            $(this).parents('.detail').find('.hide_article').hide();
        });
    }
    function news(){
        if($('#slide_news .item').length > 1){
            $('#slide_news').owlCarousel({
                items: 1,
                loop: false,
                dots: true,
                navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
                responsive : {
                    0 : {
                        nav: false,
                    },
                    1050 : {
                        nav: true,
                    }
                }
            });
        }
    }
    function credit(){
        if($('#slide_loan .item').length > 1){
            var index = $('#slide_loan .item a.active').parent().index();
            $('#slide_loan').owlCarousel({
                nav: true,
                navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
                loop: true,
                dots: false,
                startPosition: index,
                responsive : {
                    0 : {
                        items : 1,
                        margin: 0,
                    },
                    768 : {
                        items: 3,
                        margin: 20,
                    },
                    920 : {
                        items: 4,
                        margin: 40,
                    }
                }
            });
            $('#slide_loan').on('changed.owl.carousel', function() {
                setTimeout(function(){
                    var url =  $('#slide_loan .owl-item.active .item a').attr('href');
                    window.location.href = url;
                },500);
            });
        }
        $('.select-status input[type="radio"]').change(function(){
            var val = $(this).val();
            $(this).parents('form').find('.forshow').addClass('hide');
            $(this).parents('form').find('.forshow.for-'+val).removeClass('hide');
        });
    }
    function home(){
        if($('#banner .item').length > 1){
            $('#banner').owlCarousel({
                items: 1,
                nav: true,
                navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
                loop: true,
                autoplay:true,
                autoplayTimeout:5000,
                responsive : {
                    0 : {
                        dots: true,
                        nav: false,
                    },
                    768 : {
                        dots: false,
                        nav: true,
                    }
                }
            });
        }
        if($('#slide .item').length > 1){
            $('#slide').owlCarousel({
                
                margin: 20,
                nav: true,
                navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
                loop: true,
                dots: true,
                responsive : {
                    0 : {
                        items : 1,
                        margin: 0,
                    },
                    768 : {
                        items: 2,
                        margin: 20,
                    },
                    920 :{
                        items: 3,
                        margin: 20,
                    }
                }
            });
        }
        if($('#slide-customer .item').length > 1){
            $('#slide-customer').owlCarousel({
                items: 2,
                margin: 20,
                nav: true,
                navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
                loop: true,
                dots: true,
                responsive : {
                    0 : {
                        items : 1,
                        margin: 0,
                    },
                    768 : {
                        items: 2,
                        margin: 20,
                    }
                }
            });
        }
    }
    function about(){
        $('.timeline .box').hover(function(){
            $(this).addClass('active');
        },function(){
            // $(this).delay( 5000 ).removeClass('active');
        });
    }
    function modalError(text){
        $('#popup-error .txt').html(text);
        $('#popup-error').fadeIn(300);
        $('#popup-error button').off("click").click(function(){
            $('#popup-error').fadeOut();
        });
    }
    function modalSuccess(text){
        $('#popup-success .txt').html(text);
        $('#popup-success').fadeIn(300);
        $('#popup-success button').off("click").click(function(){
            $('#popup-success').fadeOut();
        });
    }
    function sizeFont(){
        var size = sessionStorage.getItem('fontSize');
        // var sizeThai = ['เล็ก', 'กลาง', 'ใหญ่'];
        var sizeThai = [text_small, text_medium, text_large];
        $('.font-size .select span').html(sizeThai[size]);
        $('body').removeClass('small');
        $('body').removeClass('middle');
        $('body').removeClass('big');
        if(size == 0){
            $('body').addClass('small');
            $('body').css({
                'font-size' : '18px',
                'line-height' : '18px'
            });
            $('.navbar .navbar-collapse a.normal').css({'padding' : '15px 33px'});
        }else if(size == 1){
            $('body').addClass('middle');
            $('body').css({
                'font-size' : '22px',
                'line-height' : '22px'
            });
            $('.navbar .navbar-collapse a.normal').css({'padding' : '15px 27px'});
        }else if(size == 2){
            $('body').addClass('big');
            $('body').css({
                'font-size' : '25px',
                'line-height' : '25px'
            });
            $('.navbar .navbar-collapse a.normal').css({'padding' : '15px 21px'});
        }
    }

    function setSizeCustomer(){
        var high = 415;
        if( $('#customer').length > 0 ){
            setTimeout(function(){
                $('#customer .box').each(function(){
                    if( $(this).height() > high) high = $(this).height();
                });
            },200);
            setTimeout(function(){
                $('#customer .box').each(function(){
                    var h = $(this).height();
                    var hx = high - h;
                    if( hx > 0){
                        $(this).find('.comment').css({'margin-bottom' : (hx)+'px'});
                    }
                });
            }, 600);
        }
    }

    function setFooterLink(){
        if($('footer').length > 0){
            $('footer .middle .box:eq(1) ul li:eq(3) a').attr('href', 'http://npa.smebank.co.th/');
        }
    }
})();
function changeFont(val){
    // var sizeThai = ['เล็ก', 'กลาง', 'ใหญ่'];
    var sizeThai = [text_small, text_medium, text_large];
    $('.font-size .select span').html(sizeThai[val]);
    $('.font-size').removeClass('open');
    $('body').removeClass('small');
    $('body').removeClass('middle');
    $('body').removeClass('big');
    sessionStorage.setItem('fontSize', val);
    if(val == 0){
        $('body').addClass('small');
        $('body').css({
            'font-size' : '19px',
            'line-height' : '19px'
        });
        $('.navbar .navbar-collapse a.normal').css({'padding' : '15px 33px'});
    }else if(val == 1){
        $('body').addClass('middle');
        $('body').css({
            'font-size' : '22px',
            'line-height' : '22px'
        });
        $('.navbar .navbar-collapse a.normal').css({'padding' : '15px 27px'});
    }else if(val == 2){
        $('body').addClass('big');
        $('body').css({
            'font-size' : '25px',
            'line-height' : '25px'
        });
        $('.navbar .navbar-collapse a.normal').css({'padding' : '15px 21px'});
    }
}
$("img").error(function () {		
     $(this).unbind("error").attr("src", "/default/img/default-image.jpg");		
});
