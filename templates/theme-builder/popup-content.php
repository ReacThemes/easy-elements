<!-- Modal Overlay -->
<div id="easyel-template-modal" class="easyel-modal-overlay">
    <div class="easyel-modal-content">
        <div class="easyel-template-error-message"></div>
        <span class="easyel-close">&times;</span>
        <div class="easyel-choose-template">
            <h2 class="easyel-choose-template">Choose Template Type</h2>
            <div class="easyel-template-type">
                <select class="easyel-builder-tmpl-type" name="easyel_builder_tmpl_type">
                    <option value="">Select Archive Type</option>
                    <option value="archive">Archive</option>
                    <option value="single">Single</option>
                </select>
            </div>
            <div class="easyel-template-type">
                <input type="text" name="easyel_builder_template_name" class="easyel-builder-template-name" placeholder="Enter template Name"/>
            </div>
        </div>
        <h2>Template Elements Condition</h2>
        <p>Where do you want to display your template?</p>

        <div id="easyel-conditions-wrapper">
            <div class="easyel-condition-row">
                <select class="easyel-include-type">
                    <option value="include">Include</option>
                    <option value="exclude">Exclude</option>
                </select>
                <select class="easyel-condition-main">
                    <option value="entire-site">Entire Site</option>
                    <option value="archives">Archives</option>
                    <option value="singular">Singular</option>
                </select>
                <select class="easyel-condition-sub">
                    <option value="all">All Archives</option>
                </select>
                <span class="easyel-remove-row">&times;</span>
            </div>
        </div>

        <button type="button" id="easyel-add-condition">+ Add Condition</button>
        <div class="easyel-modal-footer">
            <button class="easyel-cancel-btn">Cancel</button>
            <button class="easyel-save-btn">Save</button>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div id="easyel-success-modal" class="easyel-modal-overlay">
    <div class="easyel-modal-content">
        <span class="easyel-close">&times;</span>
        <div class="easyel-success-icon">
            <div class="checkmark">✓</div>
        </div>
        <h2>Template Created Successfully!</h2>
        <p>Your template has been created and is ready to use</p>
        <div class="easyel-modal-footer">
            <a href="#" id="easyel-edit-template" class="easyel-save-btn">Edit Template</a>
            <button class="easyel-cancel-btn">Close</button>
        </div>
    </div>
</div>