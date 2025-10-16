(function($) {

    (rtElementsTemplatesLibrary = {
        getTemplatesModal: function () {
            var e = this;
            return (
                e.modal ||
                    ((e.modal = elementor.dialogsManager.createWidget("lightbox", {
                        id: "rtElementsTemplatesLibrary",
                        message: 
                        `<div class="dialog-content dialog-lightbox-content">
                            
                            <div id="elementor-template-library-templates">
                                <div id="elementor-template-library-banner-ad">
                                    <img src="${rtElementsTemplatesManager.bannerAdUrl}" alt="RtElements">
                                </div>
                                <div id="rt-elements-preloader">
                                    <div class="rt-elements-preloader-inner">
                                        <div class="rt-elements-preloader-rt-shape"><img src="${rtElementsTemplatesManager.logoUrl}" alt="RtElements"></div>
                                    </div>
                                </div>
                               
                                <div id="elementor-template-library-toolbar">
                                    <div id="elementor-template-library-filter-toolbar-remote" class="elementor-template-library-filter-toolbar">
                                        <div id="elementor-template-library-cat-filter">
                                            <select id="rt-elements-template-library-filter-cat" class="elementor-template-library-cat-filter-select">
                                                <option value="">All</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="elementor-template-library-filter-text-wrapper" class="rt-elements-template-library-search-box">
                                        <label for="elementor-template-library-filter-text" class="elementor-screen-only">Search Templates:</label>
                                        <input id="elementor-template-library-filter-text" placeholder="Search">
                                        <i class="eicon-search"></i>
                                    </div>
                                </div>
                                <div id="elementor-template-library-templates-container-wrapper">
                                    <div id="elementor-template-library-templates-container" class="rt-elements-template-library-templates-container"></div>
                                </div>
                                <div id="elementor-template-library-templates-preview" class="rt-elements-template-library-templates-preview"></div>
                                <div id="rt-elements-preloader-small">
                                    <div class="rt-elements-preloader-inner">
                                        <div class="rt-elements-preloader-rt-shape"><img src="${rtElementsTemplatesManager.logoUrl}" alt="RtElements"></div>
                                    </div>
                                </div>
                            </div>
                        </div>`,
                        onShow: function () {

                            e.modal.getElements("widget").addClass("elementor-templates-modal");
                            e.modal.getElements("header").html('<div class="elementor-templates-modal__header"><div class="elementor-templates-modal__header__logo-area rt-elements-layout-library--logo-area"><div class="elementor-templates-modal__header__logo"><span class="elementor-templates-modal__header__logo__icon-wrapper e-logo-wrapper"><img src="'+rtElementsTemplatesManager.headerLogoUrl+'" alt="RtElements"></span><span class="elementor-templates-modal__header__logo__title">RT Elements</span></div></div><div class="elementor-templates-modal__header__menu-area" data-disabled="false"><div id="elementor-template-library-header-menu"><div class="elementor-template-library-menu-item" data-tab="templates">Templates</div><div class="elementor-template-library-menu-item " data-tab="pages">Pages</div><div class="elementor-template-library-menu-item elementor-active" data-tab="sections">Sections</div></div></div><div class="elementor-templates-modal__header__items-area"><div class="elementor-templates-modal__header__close elementor-templates-modal__header__close--normal elementor-templates-modal__header__item"><i class="eicon-close" aria-hidden="true" title="Close"></i><span class="elementor-screen-only">Close</span></div></div></div>')
                           
                            let activeTab = $('#rtElementsTemplatesLibrary .elementor-template-library-menu-item.elementor-active').data('tab'),
                                templatesContainer = $('#rtElementsTemplatesLibrary #elementor-template-library-templates-container'),
                                page = 1;

                            
                            rtElementsTemplatesManager.templatesContainer = templatesContainer;

                            rtElementsTemplatesLibrary.getTemplates(activeTab, page)
                            .then((templates) => {
                                if (templates) {

                                    window.rtElementsTemplatesManager.templates = templates;
                                    rtElementsTemplatesLibrary.pushTemplatesHtml(templates, activeTab, 'html');

                                    // load taxonomies in filter Selection
                                    let taxonomies = templates.taxonomies.categories,
                                        catSelectEl = $('#rt-elements-template-library-filter-cat'), // Assuming it's an ID selector
                                        optsEl = '<option value="">All</option>';

                                    taxonomies.forEach(taxonomy => {
                                        optsEl += `<option value="${taxonomy.slug}">${taxonomy.name}</option>`;
                                    });

                                    catSelectEl.html(optsEl); // Append all options at once

                                    rtElementsTemplatesLibrary.loadMoreTemplates();
                                } else {
                                    console.error('No templates returned');
                                }
                            })
                            .catch((error) => {
                                console.error('Error in template fetching:', error);
                            });
                            
                        },
                        onHide: function () {
                            "currentRequest" in window.rtElementsTemplatesLibraryModal && window.rtElementsTemplatesLibraryModal.currentRequest.reject();
                        },
                    })),
                    (window.rtElementsTemplatesLibraryModal = e.modal)),
                this.modal
            );
        },
        switchTab: function () { 

            rtElementsTemplatesLibrary.showPreloader();

            let activeTab = $(this).data('tab'),
                templatesContainer = rtElementsTemplatesManager.templatesContainer; 

            rtElementsTemplatesManager.activeTab = activeTab;
            rtElementsTemplatesLibrary.showHeaderLogo();
            rtElementsTemplatesLibrary.removeBackToTemplatesButton();
            rtElementsTemplatesLibrary.hidePreview();

            $('.rt-elements-template-library-search-box input').val('');

            $('#rtElementsTemplatesLibrary .elementor-template-library-menu-item').removeClass('elementor-active');
            $(this).addClass('elementor-active');

            templatesContainer = templatesContainer.html('');
            
            rtElementsTemplatesLibrary.getTemplates(activeTab, 1)
            .then((templates) => {
                if (templates) {
                    
                    rtElementsTemplatesLibrary.pushTemplatesHtml(templates, activeTab, 'html');
                } else {
                    console.error('No templates returned');
                }
            })
            .catch((error) => {
                console.error('Error in template fetching:', error);
            });
            
        },
        pushTemplatesHtmlBySearch: function () { 

            let searchKey = $('.rt-elements-template-library-search-box input').val(),
                activeTab = rtElementsTemplatesManager.activeTab,
                page = 1;

                rtElementsTemplatesManager.templatesContainer.html('');

                rtElementsTemplatesLibrary.getTemplates(activeTab, page, '', '', searchKey)
                .then((templates) => {
                    if (templates) {
                        
                        // save to window objects rtElementsTemplatesManager
                        window.rtElementsTemplatesManager.templates = templates;
                        rtElementsTemplatesLibrary.pushTemplatesHtml(templates, activeTab, 'html');

                    } else {
                        console.error('No templates returned');
                    }
                })
                .catch((error) => {
                    console.error('Error in template fetching:', error);
                });
        },
        pushTemplatesHtmlByGroup: function (page = 1) {
            let group = $(this).data('template-group');
           
            rtElementsTemplatesManager.templatesContainer.html('');
            rtElementsTemplatesLibrary.hideHeaderLogo();
            rtElementsTemplatesLibrary.addBackButton();

            rtElementsTemplatesLibrary.getTemplates('pages', page, '', [group] )
            .then((templates) => {
                if (templates) {
                    
                    rtElementsTemplatesLibrary.pushTemplatesHtml(templates, 'pages', 'html');

                } else {
                    console.error('No templates returned');
                }
            })
            .catch((error) => {
                console.error('Error in template fetching:', error);
            });
           
        },
        pushTemplatesHtmlByCat: function (page = 1) { 
            activeTab = rtElementsTemplatesManager.activeTab;
            let cat = $('#rt-elements-template-library-filter-cat').val();  
            
            rtElementsTemplatesManager.templatesContainer.html('');

            rtElementsTemplatesLibrary.getTemplates(activeTab, page, [cat])
            .then((templates) => {
                if (templates) {

                    // save to window objects rtElementsTemplatesManager
                    window.rtElementsTemplatesManager.templates = templates;
                    rtElementsTemplatesLibrary.pushTemplatesHtml(templates, activeTab, 'html');

                } else {
                    console.error('No templates returned');
                }
            })
            .catch((error) => {
                console.error('Error in template fetching:', error);
            });
        },
        requestIngStatusMessage: function (message = 'Loading...') { 
            const templatesContainer = $('.elementor-templates-modal .dialog-buttons-wrapper');
            let templatesHtml = `
                <strong class="rt-elements-template-requesting-status">${message}</strong>
            `;
            templatesContainer.html(templatesHtml).show();
            setTimeout(() => {
                templatesContainer.html('');
            }, 2000);
        },
        pushTemplatesHtml: function (templates, activeTab = 'pages', insertCallback = 'append') {
            const rtElementsTemplatesLibraryEl = $('#rtElementsTemplatesLibrary');
            const templatesContainer = rtElementsTemplatesManager.templatesContainer;
        
            // Determine the items to render based on activeTab
            const items = activeTab === 'templates' ? templates.taxonomies.groups : templates.layouts;
        
            // Update the active tab data attribute
            rtElementsTemplatesLibraryEl.attr('data-templates-tab', activeTab);
        
            // Generate HTML for templates
            const templatesHtml = items.map(template => {
                const isTemplateTab = activeTab === 'templates';
                const preview = isTemplateTab
                    ? ''
                    : template.preview?.url || rtElementsTemplatesManager.thumbnailPlaceholderUrl;
        
                const thumbnail = template.thumbnail || rtElementsTemplatesManager.thumbnailPlaceholderUrl;
                const attr = isTemplateTab
                    ? `data-template-group="${template.slug}"`
                    : `data-template-id="${template.id}" data-preview="${preview}"`;
                const title = isTemplateTab ? template.name : template.title;
        
                return `
                    <div ${attr}
                        style="--elementor-template-library-subscription-plan-label: &quot;Pro&quot;--elementor-template-library-subscription-plan-color: #92003B;" 
                        class="elementor-template-library-template elementor-template-library-template-remote elementor-template-library-template-${activeTab} elementor-template-library-free-template">
                        <div class="elementor-template-library-template-body">
                            <img src="${thumbnail}" alt="Template Thumbnail">
                            <div class="elementor-template-library-template-preview">
                                <i class="eicon-zoom-in-bold" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="elementor-template-library-template-footer">
                            <a data-template-id="${template.id}" class="elementor-template-library-template-action rt-elements-template-insert elementor-button e-primary">
                                <i class="eicon-file-download" aria-hidden="true"></i>
                                <span class="elementor-button-title">Insert</span>
                            </a>
                            <div class="elementor-template-library-template-name">${title}</div>
                        </div>
                    </div>
                `;
            }).join('');
        
            // Insert the generated HTML into the container
            if (insertCallback === 'html') {
                templatesContainer.html(templatesHtml);
            } else {
                templatesContainer.append(templatesHtml);
            }
        
            // Reload the Isotope layout
            rtElementsTemplatesLibrary.loadIsotop();
        
            return templatesHtml;
        },
        loadMoreTemplates: function () {  

            let page = 2; // Start from page 2 since the first page is already loaded
                loading = false, // Prevent multiple requests
                scrollableElement = $('#rtElementsTemplatesLibrary #elementor-template-library-templates-container-wrapper'), // Target your scrollable element
                debounceTimer = ''; // Add a debounced scroll event handler
        
            
            scrollableElement.on('scroll', function () {

                

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    // Detect if scrolled to the bottom of the specific scrollable element
                    if (
                        scrollableElement.scrollTop() + scrollableElement.innerHeight() >= scrollableElement[0].scrollHeight - 40 &&
                        !loading
                    ) {
                        loading = true; // Lock further requests during loading
                        
                       
                        rtElementsTemplatesLibrary.requestIngStatusMessage();

                        let activeTab = $('#rtElementsTemplatesLibrary .elementor-template-library-menu-item.elementor-active').data('tab');

        
                        // Fetch templates based on the current tab and page
                        rtElementsTemplatesLibrary.getTemplates(activeTab, page)
                            .then((templates) => {
                                if (templates) {

                                    if(templates.layouts.length > 0){
                                        // Save to the window object rtElementsTemplatesManager
                                        window.rtElementsTemplatesManager.templates = templates;
                                        rtElementsTemplatesLibrary.pushTemplatesHtml(templates, activeTab, 'append');
                                        page++; // Increment the page number
                                        
                                    }else{
                                        rtElementsTemplatesLibrary.requestIngStatusMessage('No more results found!');
                                    }
                                } else {
                                    console.error('No templates returned');
                                }
                            })
                            .catch((error) => {
                                console.error('Error fetching templates:', error);
                            })
                            .finally(() => {
                                loading = false; // Unlock further requests
                            });
                    }
                }, 150); // Adjust debounce time as needed

            });
        },
        getTemplates: function (tab = 'pages', page = 1, categories = [], groups = [], searchKey = '') {

            

            let apiUrl = rtElementsTemplatesManager.apiUrl;
               
            // Example parameters
            const params = {
                tab: tab,
                categories: categories,
                groups: groups,
                searchKey: searchKey,
                postsPerPage: 50,
                page: parseInt(page),
            };
        
            // Convert parameters to a query string
            const queryString = new URLSearchParams(params).toString();
        
            // Return the fetch promise
            return fetch(`${apiUrl}?${queryString}`)
                .then((response) => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    rtElementsTemplatesLibrary.hidePreloader();
                    return response.json();
                })
                .catch((error) => {
                    console.error('Error fetching API:', error);
                    return null; // Return null if an error occurs
                });
        },
        loadIsotop: function () { 
            let grid = '#rtElementsTemplatesLibrary #elementor-template-library-templates-container';

            let iso = new Isotope(grid, {
                itemSelector: '#rtElementsTemplatesLibrary #elementor-template-library-templates-container .elementor-template-library-template',
                layoutMode: 'masonry'
            });
        
            // Trigger layout after all images are loaded
            imagesLoaded(grid, function() {
                iso.layout();
            });
        },
        insertTemplate: function () { 


            rtElementsTemplatesLibrary.showPreloader();

            let apiUrl = rtElementsTemplatesManager.apiUrl + '/templatesData',
                templateId = $(this).data('template-id');
               
            // Example parameters
            const params = {
                templateId: templateId,
            };
        
            // Convert parameters to a query string
            const queryString = new URLSearchParams(params).toString();
        
            // Return the fetch promise
            return fetch(`${apiUrl}?${queryString}`)
                .then((response) => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then((response) => {
                 
                    $e.run("document/elements/import", { model: window.elementor.elementsModel, data: response, options: {} });
                    rtElementsTemplatesLibrary.hidePreloader();
                    rtElementsTemplatesLibrary.hideModal();
                })
                .catch((error) => {
                    console.error('Error fetching API:', error);
                    return null; // Return null if an error occurs
                });
        },
        hidePreview: function (){
            $('.rt-elements-template-library-templates-preview').hide();
            $('.rt-elements-template-library-templates-container').show();
        },
        backToTemplates: function () { 

            let activeTab = rtElementsTemplatesManager.activeTab;

            if($('.rt-elements-template-library-templates-preview').css('display') == 'none') $(`.elementor-template-library-menu-item[data-tab="${activeTab}"]`).trigger('click');
            
            if((activeTab != 'templates') && $('.rt-elements-template-library-templates-preview').css('display') != 'none'){
                rtElementsTemplatesLibrary.showHeaderLogo();
                rtElementsTemplatesLibrary.removeBackToTemplatesButton();
            }

            rtElementsTemplatesLibrary.hidePreview();
            
        },
        previewTemplate: function(){

            let previewSrc = $(this).parent().data('preview'),
                activeTab = rtElementsTemplatesManager.activeTab;
            $('.rt-elements-template-library-templates-container').hide();
            $('.rt-elements-template-library-templates-preview').html(`<img src="${previewSrc}" alt="template-preview" class="preview-image"/>`).show();

            const tabTextMap = {
                pages: 'Back to pages',
                sections: 'Back to sections',
            };

            btnText = tabTextMap[activeTab] || 'Back to templates';

            rtElementsTemplatesLibrary.hideHeaderLogo();
            rtElementsTemplatesLibrary.addBackButton(btnText);

        },
        hideModal: function () { 
            window.rtElementsTemplatesLibraryModal.hide();
        },
        showTemplatesModal: function () {

            rtElementsTemplatesLibrary.getTemplatesModal().show(),
            $(window).trigger("resize");

        },
        showPreloader: function () { 
            $('#rt-elements-preloader').show();
        },
        hidePreloader: function () { 
            setTimeout(() => {
                $('#rt-elements-preloader').hide();
            }, 1000);
        },
        previewLoaded: function () {

            rtElementsTemplatesLibrary.loadMoreTemplates();
            
            let interval = setInterval(function () {
                window.elementor.$previewContents.find(".elementor-add-new-section").length && (rtElementsTemplatesLibrary.addButton(), clearInterval(interval));
            }, 100);

            window.elementor.$previewContents.on("click.rtElementsTemplatesLibrary", ".rt-elements-add-template-button", rtElementsTemplatesLibrary.showTemplatesModal);

            $( document )
            .on( 'click.rtElementsTemplatesLibrary', '#rtElementsTemplatesLibrary .elementor-template-library-menu-item', this.switchTab )
            .on( 'click.rtElementsTemplatesLibrary', '#rtElementsTemplatesLibrary .elementor-templates-modal__header__close', this.hideModal)
            .on( 'click.rtElementsTemplatesLibrary', '#rtElementsTemplatesLibrary .elementor-template-library-template-templates', this.pushTemplatesHtmlByGroup)
            .on( 'click.rtElementsTemplatesLibrary', '#rtElementsTemplatesLibrary #elementor-template-library-header-preview-back', this.backToTemplates)
            .on( 'change.rtElementsTemplatesLibrary', '#rtElementsTemplatesLibrary #rt-elements-template-library-filter-cat', this.pushTemplatesHtmlByCat)
            .on( 'keyup.rtElementsTemplatesLibrary', '#rtElementsTemplatesLibrary .rt-elements-template-library-search-box', this.pushTemplatesHtmlBySearch)
            .on( 'click.rtElementsTemplatesLibrary', '#rtElementsTemplatesLibrary .elementor-template-library-template[data-preview] .elementor-template-library-template-body', this.previewTemplate)
            .on( 'click.rtElementsTemplatesLibrary', '#rtElementsTemplatesLibrary .rt-elements-template-insert', this.insertTemplate);

        },
        hideHeaderLogo: function () { 
            $(".rt-elements-layout-library--logo-area .elementor-templates-modal__header__logo").hide();
        },
        showHeaderLogo: function () { 
            $(".rt-elements-layout-library--logo-area .elementor-templates-modal__header__logo").show();
        },
        addButton: function () {
            let elementorBtn = window.elementor.$previewContents.find(".elementor-add-template-button"),
                rtBtn = $('<div class="rt-elements-add-template-button" title="Add RT Elements Template"><img src="'+rtElementsTemplatesManager.buttonIcon+'" alt=""></div>');
                elementorBtn.after(rtBtn);
        },
        addBackButton: function (buttonText = 'Back to templates') {
            let BtnContainer = $(".rt-elements-layout-library--logo-area"),
                btn = $(`<div id="elementor-template-library-header-preview-back"><i class="eicon-" aria-hidden="true"></i><span>${buttonText}</span></div>`);
                $(BtnContainer).find('#elementor-template-library-header-preview-back').remove();
                BtnContainer.append(btn);
        },
        removeBackToTemplatesButton: function () {
            $(".rt-elements-layout-library--logo-area #elementor-template-library-header-preview-back").remove();
        },
        
        init: function () {
            window.elementor.on("preview:loaded", window._.bind(rtElementsTemplatesLibrary.previewLoaded, rtElementsTemplatesLibrary));
        },
    }),
    $(window).on("elementor:init", rtElementsTemplatesLibrary.init);
    
})(jQuery);