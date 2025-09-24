(function($) {
    "use strict";
    
    function initEeTabs($scope) {
        const tabsWrapper = $scope[0].querySelector(".ee-tabs-wrapper");
        if (!tabsWrapper) return;

        const tabs = tabsWrapper.querySelectorAll(".ee-tab-titles li");
        const contents = tabsWrapper.querySelectorAll(".ee-tab-content");

        function slideOut(element, duration = 300) {
            return new Promise((resolve) => {
                element.style.transition = `opacity ${duration}ms, transform ${duration}ms`;
                element.style.opacity = 0;
                element.style.transform = "translateY(20px)";
                setTimeout(() => {
                    element.style.display = "none";
                    resolve();
                }, duration);
            });
        }

        function slideIn(element, duration = 300) {
            return new Promise((resolve) => {
                element.style.display = "block";
                element.style.opacity = 0;
                element.style.transform = "translateY(20px)";
                element.style.transition = `opacity ${duration}ms, transform ${duration}ms`;
                setTimeout(() => {
                    element.style.opacity = 1;
                    element.style.transform = "translateY(0)";
                }, 10);
                setTimeout(() => {
                    resolve();
                }, duration);
            });
        }

        tabs.forEach(tab => {
            tab.addEventListener("click", async function () {
                tabs.forEach(t => t.classList.remove("active"));

                const currentContent = Array.from(contents).find(c => c.style.display !== "none");
                const targetContent = tabsWrapper.querySelector("#" + this.dataset.tab);

                if (!targetContent || currentContent === targetContent) {
                    this.classList.add("active");
                    return;
                }

                if (currentContent) {
                    await slideOut(currentContent, 300);
                }

                contents.forEach(c => c.classList.remove("active"));

                this.classList.add("active");

                await slideIn(targetContent, 300);
                targetContent.classList.add("active");
            });
        });

        // Default active
        if (tabs.length && contents.length) {
            tabs[0].classList.add("active");
            contents.forEach(c => {
                c.style.display = "none";
                c.style.opacity = 0;
                c.style.transform = "translateY(20px)";
            });
            contents[0].style.display = "block";
            contents[0].style.opacity = 1;
            contents[0].style.transform = "translateY(0)";
            contents[0].classList.add("active");
        }
    }

    function handleTabDirection($scope) {
        const wrapper = $scope.find(".ee-tabs-wrapper");
        if (!wrapper.length) return;
        const direction = wrapper.data("tab-direction"); 
        const settings = $scope.data("settings") || {};
        if (settings && settings.tab_layout_direction) {
            wrapper.addClass("direction-" + settings.tab_layout_direction);
        }
        const iconPosition = wrapper.data("icon-position");
        if (iconPosition) {
            wrapper.addClass("icon-position-" + iconPosition);
        }
    }

    $(window).on("elementor/frontend/init", function () {
        elementorFrontend.hooks.addAction("frontend/element_ready/global", initEeTabs);
        elementorFrontend.hooks.addAction("frontend/element_ready/global", handleTabDirection);
    });

})(jQuery);
