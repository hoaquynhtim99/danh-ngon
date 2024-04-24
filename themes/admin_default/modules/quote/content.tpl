<!-- BEGIN: main -->
<link rel="stylesheet" href="{ASSETS_STATIC_URL}/js/select2/select2.min.css">
<script src="{ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
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
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addAuthorModal">
                    {LANG.add_authour}
                </button>
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
                    <!-- BEGIN: btn_add -->
                    <button type="submit" class="btn btn-primary" name="add_return">{LANG.add_and_return}</button>
                    <button type="submit" class="btn btn-primary" name="add_again">{LANG.add_again}</button>
                    <!-- END: btn_add -->
                    <!-- BEGIN: btn_edit -->
                    <button type="submit" class="btn btn-primary">{GLANG.submit}</button>
                    <!-- END: btn_edit -->
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="addAuthorModal" tabindex="-1" role="dialog" aria-labelledby="addAuthorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAuthorModalLabel">{LANG.add_authour}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addAuthorForm">
                    <div class="form-group">
                        <label>{LANG.name_authour}</label>
                        <input type="text" class="form-control" id="authorName" name="name_author" required>
                    </div>
                    <input type="hidden" name="add_author" value="{NV_CHECK_SESSION}">
                    <button type="button" class="btn btn-primary" id="submitAuthorBtn">{GLANG.submit}</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('select').select2();
    });
</script>

<script>
    $(document).ready(function() {
        $('#submitAuthorBtn').click(function() {
            var $form = $('#addAuthorForm');

            $.ajax({
                url: location.href,
                type: 'POST',
                data: $form.serialize(),
                success: function(response) {
                    $('#addAuthorModal').modal('hide');
                    location.reload();
                }
            });
        });
    });
</script>
<!-- END: main -->
