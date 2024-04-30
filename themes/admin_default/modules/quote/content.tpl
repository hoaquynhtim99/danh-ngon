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
                <label class="col-sm-6 control-label" for="element_catids">{LANG.cats_title}</label>
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
                <label class="col-sm-6 control-label" for="element_author_id">{LANG.name_authour} </label>
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
                <label class="col-sm-6 control-label" for="element_title">{LANG.Tags}</label>
                <div class="col-sm-18 col-lg-10">
                    <select class="form-control" name="tagids[]" multiple data-placeholder="{LANG.please_select}">
                        <!-- BEGIN: tag -->
                        <option value="{TAG.key}"{TAG.selected}>{TAG.title}</option>
                        <!-- END: tag -->
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
                    <select id="keyword_select2" class="form-control" name="keywords[]" multiple="multiple"></select>
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
                        {LANG.back}
                    </a>
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
                <div class="panel panel-default">
                    <div class="panel-body">
                        <form id="addAuthorForm" class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-6 control-label" for="element_name_author">{LANG.name_authour}</label>
                                <div class="col-sm-18 col-lg-10">
                                    <input type="text" class="form-control" id="element_name_author" name="name_author" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-6 control-label" for="element_alias">{LANG.alias}</label>
                                <div class="col-sm-18 col-lg-10">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="element_alias" name="alias">
                                        <span class="input-group-btn">
                                    <button class="btn btn-default" type="button" onclick="get_authour_alias('{DATA.id}', '{NV_CHECK_SESSION}')">
                                        <i class="fa fa-retweet"></i>
                                    </button>
                                </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-18 col-sm-offset-6">
                                    <input type="hidden" name="add_author" value="{NV_CHECK_SESSION}">
                                    <button type="button" class="btn btn-primary" id="submitAuthorBtn">{GLANG.submit}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#submitAuthorBtn').click(function() {
            var $form = $('#addAuthorForm');
            $.ajax({
                url: location.href,
                type: 'POST',
                data: $form.serialize(),
            }).done(function(response) {
                if (response['res'] == 'success') {
                    $('#addAuthorModal').modal('hide');
                    location.reload();
                } else {
                    alert(response['mess']);
                }
            })
        });
    });
</script>

<!-- BEGIN: getalias -->
<script type="text/javascript">
    $(document).ready(function() {
        var autoAlias = true;
        $('#element_name_author').on('change', function() {
            if (autoAlias) {
                get_authour_alias('{DATA.id}', '{NV_CHECK_SESSION}')
            }
        });
        $('#element_alias').on('keyup', function() {
            if (trim($(this).val()) == '') {
                autoAlias = true;
            } else {
                autoAlias = false;
            }
        });
    })
</script>
<!-- END: getalias -->

<script>
    $(document).ready(function() {
        $('select').select2({
            placeholder: '{LANG.please_select}',
            tags: true,
            autoClear: true,
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#keyword_select2').select2({
            tags: true,
            placeholder: '{LANG.please_select}',
            minimumInputLength: 1,
            tokenSeparators: [',', '\n'],
        });
        var keywords = '{DATA.keywords}';
        if (keywords != '') {
            var keyword = keywords.split(',');
            for (var i = 0; i < keyword.length; i++) {
                var newOption = new Option(keyword[i], keyword[i], true, true);
                $('#keyword_select2').append(newOption).trigger('change');
            }
        }
    });
</script>
<!-- END: main -->
