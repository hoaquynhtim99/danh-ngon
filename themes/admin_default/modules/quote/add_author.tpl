<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<h2><i class="fa fa-th-large" aria-hidden="true"></i> {CAPTION}</h2>
<p class="text-info"><span class="fa-required text-danger">(<em class="fa fa-asterisk"></em>)</span> {LANG.is_required}</p>
<div class="panel panel-default">
    <div class="panel-body">
        <form method="post" action="{FORM_ACTION}" class="form-horizontal">
            <div class="form-group">
                <label class="col-sm-6 control-label" for="element_name_author">{LANG.add_author} <span class="fa-required text-danger">(<em class="fa fa-asterisk"></em>)</span>:</label>
                <div class="col-sm-18 col-lg-10">
                    <input type="text" class="form-control" id="element_name_author" name="name_author" value="{DATA.name_author}">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label" for="element_alias">{LANG.alias}</label>
                <div class="col-sm-18 col-lg-10">
                    <div class="input-group">
                        <input type="text" class="form-control" id="element_alias" name="alias" value="{DATA.alias}">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button" onclick="get_author_alias('{DATA.id}', '{NV_CHECK_SESSION}')">
                                <i class="fa fa-retweet"></i>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label" for="element_description">{LANG.description}:</label>
                <div class="col-sm-18 col-lg-10">
                    <textarea class="form-control" rows="3" name="description" id="element_description">{DATA.description}</textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label" for="element_bodyhtml">{LANG.bodyhtml_author}:</label>
                <div class="col-sm-18 col-lg-10">
                    <textarea class="form-control" rows="5" name="bodyhtml" id="element_bodyhtml">{DATA.bodyhtml}</textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label" for="element_image">{LANG.image_author}:</label>
                <div class="col-sm-18 col-lg-10">
                    <div class="input-group">
                        <input type="text" id="element_image" name="image" value="{DATA.image}" class="form-control">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button" id="element_image_pick"><i class="fa fa-file-image-o"></i></button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-18 col-sm-offset-6">
                    <input type="hidden" name="save" value="{NV_CHECK_SESSION}">
                    <!-- BEGIN: btn_add -->
                    <button type="submit" class="btn btn-primary" name="add_return">
                        <i class="fa fa-floppy-o"></i>
                        {LANG.add_and_return}</button>
                    <button type="submit" class="btn btn-primary" name="add_again">
                        <i class="fa fa-floppy-o"></i>
                        {LANG.add_again}</button>
                    <!-- END: btn_add -->
                    <!-- BEGIN: btn_edit -->
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-floppy-o"></i>
                        {GLANG.submit}</button>
                    <!-- END: btn_edit -->
                    <a href="{URL_BACK}" class="btn btn-default">
                        <i class="fa fa-reply"></i>
                        {LANG.back}</a>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- BEGIN: getalias -->
<script type="text/javascript">
    $(document).ready(function() {
        var autoAlias = true;
        $('#element_name_author').on('change', function() {
            if (autoAlias) {
                get_author_alias('{DATA.id}', '{NV_CHECK_SESSION}');
            }
        });
        $('#element_alias').on('keyup', function() {
            if (trim($(this).val()) == '') {
                autoAlias = true;
            } else {
                autoAlias = false;
            }
        });
    });
</script>
<!-- END: getalias -->
<script type="text/javascript">
    $(document).ready(function(){
        $('#element_image_pick').on('click', function(e) {
            e.preventDefault();
            nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=element_image&path={UPLOAD_PATH}&type=image&currentpath={UPLOAD_CURRENT}", "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
        });
    });
</script>
<!-- END: main -->
