(function($){
    // =================================
    // GSAP & ScrollTrigger
    // =================================
    if (typeof gsap === "undefined" || typeof ScrollTrigger === "undefined") return;
    gsap.registerPlugin(ScrollTrigger);
    // =================================
    // Elementor Animations
    // =================================
    var easyel_runAnimations = function($scope){

        function splitTextNodes($el, mode){
            $el.contents().each(function(){
                if(this.nodeType === 3){ // Text node
                    if(mode === 'chars'){
                        var chars = this.nodeValue.split('').map(l => '<em class="easy-split-letter">'+(l===' ' ? '&nbsp;' : l)+'</em>').join('');
                        $(this).replaceWith(chars);
                    } else if(mode === 'words'){
                        var words = this.nodeValue.split(' ').map(w => '<em class="easy-split-word">'+w+'</em>').join(' ');
                        $(this).replaceWith(words);
                    }
                } else if(this.nodeType === 1){ // Element node
                    splitTextNodes($(this), mode);
                }
            });
        }

        $scope.find('[data-eel-animation]').each(function(){
            var $wrap = $(this);
            var target = $wrap.find('.e-e-title');
            var animType = $wrap.data('eel-animation');
            if(!animType || animType==='default' || !target.length) return;

            switch(animType){
                case 'split-text':
                    splitTextNodes(target,'chars');
                    if(target.find('.easy-split-letter').length){
                        gsap.from(target.find('.easy-split-letter'), {
                            y:50, opacity:0, stagger:0.05, duration:0.6,
                            scrollTrigger:{trigger:$wrap[0], start:"top 80%"}
                        });
                    }
                    break;

                case 'split-words':
                    splitTextNodes(target,'words');
                    if(target.find('.easy-split-word').length){
                        gsap.from(target.find('.easy-split-word'), {
                            y:50, opacity:0, stagger:0.1, duration:0.6,
                            scrollTrigger:{trigger:$wrap[0], start:"top 80%"}
                        });
                    }
                    break;

                case 'typewriter':
                    var t = target.text(); target.text('');
                    gsap.to({length:0},{length:t.length,duration:2,ease:"none",
                        scrollTrigger:{trigger:$wrap[0], start:"top 80%"},
                        onUpdate:function(){ target.text(t.substr(0, Math.floor(this.targets()[0].length))); }
                    });
                    break;

                case 'text-bounce':
                    gsap.from(target,{y:20, opacity:0, ease:"bounce.out", duration:1,
                        scrollTrigger:{trigger:$wrap[0], start:"top 80%"}});
                    break;

                case 'split-lines': 
                case 'split-lines-left':
                case 'split-lines-up':
                    if(typeof SplitText !== "undefined"){
                        document.fonts.ready.then(()=>{
                            let split = new SplitText(target,{type:"lines,words", wordsClass:"split-words", linesClass:"split-lines"});
                            let vars = {stagger:0.15, duration:0.8, ease:"power3.out", scrollTrigger:{trigger:$wrap[0], start:"top 80%"}};
                            if(animType==='split-lines-left') vars.x=80, vars.opacity=0;
                            else if(animType==='split-lines-up') vars.y=-40, vars.opacity=0;
                            else vars.y=60, vars.opacity=0;
                            gsap.from(split.lines, vars);
                        });
                    }
                    break;
            }
        });
    };


	// =================================
	// Horizontal Scroll Section
	// =================================
	gsap.registerPlugin(ScrollTrigger);
		gsap.utils.toArray('.eel-scroll-image').forEach(section => {
		const images = section.querySelectorAll('.per-item');
		
		// Calculate total width
		let totalWidth = 0;
		images.forEach(img => {
			const style = getComputedStyle(img);
			const marginRight = parseFloat(style.marginRight) || 0;
			totalWidth += img.offsetWidth + marginRight;
		});

		// Set section width
		section.style.width = totalWidth + "px";

		// Horizontal scroll
		gsap.to(section, {
			x: () => `-${totalWidth - section.parentElement.offsetWidth}px`,
			ease: "none",
			scrollTrigger: {
			trigger: section.parentElement,
			start: "center center",
			end: () => "+=" + (totalWidth - section.parentElement.offsetWidth),
			pin: true,
			scrub: true,
			anticipatePin: 1,
			}
		});
	});


    // =================================
    // DOM Content Loaded Global Animations
    // =================================
    document.addEventListener('DOMContentLoaded', function(){
        // =================================
		// Parallax Backgrounds
		// =================================
		gsap.utils.toArray(".eel-parallax-bg").forEach(section=>{
            let size = section.dataset.parallaxSize || "180%";
            section.style.backgroundSize = size;

            let y = {value: 0}, 
                smooth = {value: 0};

            ScrollTrigger.create({
                trigger: section,
                start: "top bottom",
                end: "bottom top",
                scrub: 0.6,
                onUpdate: self => y.value = -self.progress * 80
            });

            gsap.ticker.add(()=>{
                smooth.value += (y.value - smooth.value) * 0.12;
                section.style.backgroundPosition = `center ${smooth.value}px`;
            });
        });

        // =================================
        // Vertical scroll images
        // =================================
        gsap.utils.toArray('.eel-vertical-scroll-img').forEach(div => {
            let yVal = div.dataset.scrollY || "20%";
            gsap.set(div, { y: 0 });
            gsap.to(div, {
                y: yVal,
                ease: "none",
                scrollTrigger: {
                    trigger: div,
                    pin: false,
                    scrub: 0.6
                }
            });
        });
       

        // Horizontal text scroll
        gsap.utils.toArray('.eel-hr-scroll-text').forEach(text=>{
            let dir=text.dataset.parallaxDirection||"left";
            let percent=text.dataset.parallaxPercent||36;
            let startX = dir==="left"? percent+"%" : "-"+percent+"%";
            gsap.set(text,{x:startX});
            gsap.to(text,{x:"0%", ease:"none", scrollTrigger:{trigger:text,start:"top bottom",end:"bottom top",scrub:0.5}});
        });

    
        // Zoom Out (small ➝ big)
        gsap.utils.toArray('.eel-hr-scroll-container').forEach(div => {
            const zoomWidth = div.dataset.zoomWidth || 90;
            const start     = div.dataset.zoomStart || "top 80%";
            const end       = div.dataset.zoomEnd   || "top 30%";

            gsap.set(div, { width: zoomWidth + "%", margin: "0 auto" });
            gsap.to(div, {
                width: "100%",
                ease: "none",
                scrollTrigger: {
                    trigger: div,
                    start: start,
                    end: end,
                    scrub: 0.5
                }
            });
        });

        // Zoom In (big ➝ small)
        gsap.utils.toArray('.eel-hr-scroll-container-small').forEach(div => {
            const zoomWidth = div.dataset.zoomWidth || 90;
            const start     = div.dataset.zoomStart || "top 80%";
            const end       = div.dataset.zoomEnd   || "top 30%";

            gsap.set(div, { width: "100%", margin: "0 auto" });
            gsap.to(div, {
                width: zoomWidth + "%",
                ease: "none",
                scrollTrigger: {
                    trigger: div,
                    start: start,
                    end: end,
                    scrub: 0.5
                }
            });
        });  
        
        
        gsap.registerPlugin(ScrollTrigger);
        document.querySelectorAll('.eel-flip-img-scroll').forEach(container => {
            const img = container.querySelector('img');
            if (!img) return;
            const rotateX = parseFloat(container.dataset.flipRotateX) || 30;
            gsap.fromTo(img, 
                { rotateX: -10 },  
                { 
                    rotateX: rotateX,
                    ease: "none",
                    scrollTrigger: {
                        trigger: container,
                        start: "top bottom",
                        end: "bottom top",
                        scrub: true,
                    }
                }
            );
        });



        // Image width animation
        gsap.utils.toArray('.eel-hr-scroll-image-size').forEach(img=>{
            gsap.set(img,{width:"0%",margin:"0 auto",display:"block"});
            gsap.to(img,{width:"100%", ease:"none", scrollTrigger:{trigger:img,start:"top 80%",end:"top 30%",scrub:0.5}});
        });

        // Custom cursor
        if(document.querySelector(".eel-cursor") && document.querySelector(".eel-cursor2")){
            let cursor = document.querySelector(".eel-cursor");
            let cursor2 = document.querySelector(".eel-cursor2");
            let mouseX=0, mouseY=0;
            let cursorScale = document.querySelectorAll(".e-e-title, a");

            gsap.to({},0.016,{repeat:-1,onRepeat:()=>{
                gsap.set(cursor,{css:{left:mouseX,top:mouseY}});
                gsap.to(cursor2,{duration:0.3,css:{left:mouseX,top:mouseY}});
            }});

            window.addEventListener("mousemove", e=>{ mouseX=e.clientX; mouseY=e.clientY; });

            cursorScale.forEach(el=>{
                el.addEventListener("mouseenter", ()=>{
                    cursor.classList.add("eel-grow");
                    if(el.classList.contains("ee-small")){ cursor.classList.remove("eel-grow"); cursor.classList.add("eel-grow-small"); }
                });
                el.addEventListener("mouseleave", ()=>{
                    cursor.classList.remove("eel-grow");
                    cursor.classList.remove("eel-grow-small");
                });
            });
        }
    });

    // =================================
    // Elementor Hook
    // =================================
    $(window).on('elementor/frontend/init', function(){
        // Run on widgets
        elementorFrontend.hooks.addAction('frontend/element_ready/widget', easyel_runAnimations);
        // Run on sections
        elementorFrontend.hooks.addAction('frontend/element_ready/section', easyel_runAnimations);
        // Run on columns
        elementorFrontend.hooks.addAction('frontend/element_ready/column', easyel_runAnimations);
    });


    function initEasyelCursorHover($scope){
        var cursor = $scope.find(".easyel-cursor-hover")[0];
        var projects = $scope.find(".easyel-cursor-hover-list");
        if(!cursor || !projects.length) return;
        gsap.set(cursor, {scale: 0.1, autoAlpha: 0, transformOrigin: "center center", position: "fixed", top:0, left:0, xPercent:-50, yPercent:-50});

        let mouseX = 0, mouseY = 0;
        let posX = 0, posY = 0;
        
        window.addEventListener("mousemove", e => {
            mouseX = e.clientX;
            mouseY = e.clientY;
        });

        gsap.ticker.add(() => {
            posX += (mouseX - posX) * 0.15;
            posY += (mouseY - posY) * 0.15;
            gsap.set(cursor, {x: posX, y: posY});
        });

        projects.each(function(){
            const project = this;
            const image = project.dataset.image;

            project.addEventListener("mouseenter", ()=>{
                cursor.style.backgroundImage = `url(${image})`;
                cursor.style.backgroundSize = "cover";
                cursor.style.backgroundPosition = "center";
                gsap.to(cursor, { scale:1, autoAlpha:1, duration:0.3, ease:"power3.out" });
            });

            project.addEventListener("mouseleave", ()=>{
                gsap.to(cursor, { scale:0.1, autoAlpha:0, duration:0.3, ease:"power3.out" });
            });
        });
    }

    // Elementor frontend init
    $(window).on('elementor/frontend/init', function(){
        elementorFrontend.hooks.addAction('frontend/element_ready/widget', initEasyelCursorHover);
        elementorFrontend.hooks.addAction('frontend/element_ready/section', initEasyelCursorHover);
    });

    function initParallaxOnScope($scope) {
        var $containers = $scope.find('.eel-mouse-move-paralax');

        // if root scope is document, also include top-level containers
        if ($scope.is(document) || $scope.is(window) || $scope.length === 0) {
            $containers = $('.eel-mouse-move-paralax');
        }

        $containers.each(function(){
            var $el = $(this);

            // avoid double-init
            if ($el.data('eelParallaxInit')) return;
            $el.data('eelParallaxInit', true);

            var rect = $el[0].getBoundingClientRect();
            var mouse = { x: 0, y: 0, moved: false };
            var $items = $el.find('img');

            // ensure container has relative position
            if ( window.getComputedStyle($el[0]).position === 'static' ) {
                $el.css('position', 'relative');
            }

            // pointer support: mouse + touch
            $el.on('mousemove.eelParallax touchmove.eelParallax', function(e){
                var clientX, clientY;
                if ( e.type.indexOf('touch') === 0 ) {
                    var t = e.originalEvent && e.originalEvent.touches ? e.originalEvent.touches[0] : null;
                    if (!t) return;
                    clientX = t.clientX;
                    clientY = t.clientY;
                } else {
                    clientX = e.clientX;
                    clientY = e.clientY;
                }

                rect = $el[0].getBoundingClientRect();
                mouse.moved = true;
                mouse.x = clientX - rect.left;
                mouse.y = clientY - rect.top;
            });

            // update rect on resize/scroll
            $(window).on('resize.eelParallax scroll.eelParallax', function(){
                rect = $el[0].getBoundingClientRect();
            });

            // animation loop
            (function loop(){
                requestAnimationFrame(function(){
                    if (mouse.moved) {
                        $items.each(function(index){
                            var $item = $(this);
                            var depthAttr = parseFloat($item.attr('data-depth'));
                            var depth = !isNaN(depthAttr) ? depthAttr : Math.min(1, (index + 1) * 0.12);

                            var factorX = (mouse.x - rect.width / 2) / rect.width;
                            var factorY = (mouse.y - rect.height / 2) / rect.height;
                            var movementX = factorX * (-100) * depth;
                            var movementY = factorY * (-100) * depth;

                            gsap.to($item, {
                                x: movementX,
                                y: movementY,
                                duration: 0.6,
                                ease: "power2.out"
                            });
                        });
                        mouse.moved = false;
                    }
                    loop();
                });
            })();

        }); // each container
    }

    // init on document ready (frontend)
    $(document).ready(function(){
        initParallaxOnScope($(document));
    });

    // Elementor hooks (safe check)
    if (typeof elementorFrontend !== "undefined" && elementorFrontend.hooks) {
        elementorFrontend.hooks.addAction('frontend/element_ready/global', function($scope){
            initParallaxOnScope($scope);
        });
    }
    
})(jQuery);


(function($){
    "use strict";

    function initCursorHoverOnScope($scope) {
        if (!$scope || $scope.length === 0) return;

        var $containers = $scope.find('.eel-mouse-hover-preview');
        if ($containers.length === 0) return;

        // create main follower cursor (only once)
        if ($('body').find('.eel-hover-cursor-follower').length === 0) {
            $('body').append('<span class="eel-hover-cursor eel-hover-cursor-follower"></span>');
        }
        var $cursor = $('body').find('.eel-hover-cursor-follower');
        gsap.set($cursor, { xPercent: -50, yPercent: -50, opacity: 1 });
        var setX = gsap.quickTo($cursor, "x", { duration: 0.2, ease: "expo.out" });
        var setY = gsap.quickTo($cursor, "y", { duration: 0.2, ease: "expo.out" });

        $containers.each(function(){
            var $el = $(this);
            if ($el.data('eelCursorHoverInit')) return;
            $el.data('eelCursorHoverInit', true);

            var hoverText = $el.data('hover-text') || 'View Details';
            var bgColor = $el.data('hover-bg') || '#000000';
            var textColor = $el.data('hover-color') || '#ffffff';

            // create hover cursor (unique per container)
            var $cursorHover = $('<span class="eel-hover-cursor eel-cursor-hover"></span>')
                .html(hoverText) // allow <br> tags
                .appendTo('body');

            gsap.set($cursorHover, { xPercent: -50, yPercent: -50, scale: 0, opacity: 0, backgroundColor: bgColor, color: textColor });

            var setXHover = gsap.quickTo($cursorHover, "x", { duration: 0.2, ease: "expo.out" });
            var setYHover = gsap.quickTo($cursorHover, "y", { duration: 0.2, ease: "expo.out" });

            var $items = $el.find('img');

            // move cursors
            $el.on('mousemove.eelCursorHover touchmove.eelCursorHover', function(e){
                var clientX, clientY;
                if (e.type.indexOf('touch') === 0) {
                    var t = e.originalEvent.touches ? e.originalEvent.touches[0] : null;
                    if (!t) return;
                    clientX = t.clientX;
                    clientY = t.clientY;
                } else {
                    clientX = e.clientX;
                    clientY = e.clientY;
                }
                setX(clientX);
                setY(clientY);
                setXHover(clientX);
                setYHover(clientY);
            });

            // show hover cursor on image enter
            $items.on('mouseenter', function(){
                gsap.to($cursorHover, { scale: 1, opacity: 1, duration: 0.1, ease: "sine.inOut" });
            });

            $items.on('mouseleave', function(){
                gsap.to($cursorHover, { scale: 0, opacity: 0, duration: 0.1, ease: "sine.inOut" });
            });
        });
    }

    // frontend init
    $(document).ready(function(){
        initCursorHoverOnScope($(document));
    });

    // Elementor editor support
    if (typeof elementorFrontend !== "undefined" && elementorFrontend.hooks) {
        elementorFrontend.hooks.addAction('frontend/element_ready/global', function($scope){
            initCursorHoverOnScope($scope);
        });
    }

})(jQuery);
