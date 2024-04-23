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
                <label class="col-sm-6 control-label" for="element_catids">{LANG.cats_title} <span class="fa-required text-danger">(<em class="fa fa-asterisk"></em>)</span>:</label>
                <div class="col-sm-18 col-lg-10">
                    <select class="form-control" name="catids">
                        <option value="0" selected disabled>{LANG.please_select}</option>
                        <!-- BEGIN: cat -->
                        <option value="{CAT.key}"{CAT.selected}>{CAT.title}</option>
                        <!-- END: cat -->
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label" for="element_author_id">{LANG.name_authour} <span class="fa-required text-danger">(<em class="fa fa-asterisk"></em>)</span>:</label>
                <div class="col-sm-18 col-lg-10">
                    <select class="form-control" name="author_id">
                        <option value="0" selected disabled>{LANG.please_select}</option>
                        <!-- BEGIN: author -->
                        <option value="{AUTHOR.key}"{AUTHOR.selected}>{AUTHOR.name}</option>
                        <!-- END: author -->
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label" for="element_content">{LANG.content_content} <span class="fa-required text-danger">(<em class="fa fa-asterisk"></em>)</span>:</label>
                <div class="col-sm-18 col-lg-10">
                    <textarea class="form-control" id="element_content" name="content" rows="5">{DATA.content}</textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label" for="element_keywords">{LANG.keywords}: </label>
                <div class="col-sm-18 col-lg-10">
                    <input type="text" name="keywords" class="form-control" id="element_keywords" value="{DATA.keywords}">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-18 col-sm-offset-6">
                    <input type="hidden" name="save" value="{NV_CHECK_SESSION}">
                    <button type="submit" class="btn btn-primary">{GLANG.submit}</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- END: main -->
