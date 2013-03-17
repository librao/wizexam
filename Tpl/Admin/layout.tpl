<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>{$TITLE}</title>
	<link href="__PUBLIC__/css/admin/style.css" rel="stylesheet" type="text/css" />
	<link href="__PUBLIC__/js/admin/tbox/box.css" rel="stylesheet" type="text/css" />
	<link href="__PUBLIC__/css/admin/jquery-ui-1.8.4.custom.css" type="text/css" />
	<script type="text/javascript" src="__PUBLIC__/js/libs/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="__PUBLIC__/js/admin/common.js"></script>
	<script type="text/javascript" src="__PUBLIC__/js/admin/tbox/box.js"></script>
	<script type="text/javascript" src="__PUBLIC__/js/admin/datatable/jquery.dataTables.min.js"></script>
</head>
<body>
<script type="text/javascript">
var librao = {};
librao.cookiesPrefix = 'libnews_';
librao.Options = {
    'oLanguage': {
        'sSearch': '搜索:',
        'sProcessing': '数据加载中...',
        'sLengthMenu': '每页显示 _MENU_ 条数据',
        'sZeroRecords': '没有检索到数据',
        'sInfo': '第 _START_ 到第 _END_ 条，当前条件共有 _TOTAL_ 条数据',
        'sInfoFiltered': '(共 _MAX_ 条数据)',
        'sInfoEmpty': '沒有数据',
        'oPaginate': {
            'sFirst': '首页',
            'sPrevious': '上一页',
            'sNext': '下一页',
            'sLast': '最后一页'
        }
    },
    'bJQueryUI': true,
    'sPaginationType': 'full_numbers',
    "bProcessing": true,
    "bServerSide": true,
    "aLengthMenu": [ 30, 60, 90 ],
    "iDisplayLength": 30
};

// DataTable
librao.dataTable = function(options){
    var oTable;
    var dataTableOptions;
	dataTableOptions = $.extend(librao.Options, options || {});
    dataTableOptions.sAjaxSource = $('#js_DataTable').attr('sAjaxSource').toString();
    dataTableOptions.fnDrawCallback = function(){
        // 行换色
        $("#js_DataTable tbody tr").hover(
            function () {
                $(this).addClass("bg_hover");
            },
            function () {
                $(this).removeClass("bg_hover");
            }
        );
        $('#js_DataTable .js_delete_item').click(deleteItems);
    }
    oTable = $('#js_DataTable').dataTable(dataTableOptions);
}

function deleteItems(){
    var _that = $(this);
    var ids = _that.attr('ids').toString();
    if(ids == '' || !confirm('删除成功后将无法恢复，确认继续？')) return false;
    $.post("{:U('Admin/Wiki/doDelCateOther')}", {ids:ids}, function(res){
        if(res == '1') {
            _that.parents('tr').eq(0).remove();
            ui.success('保存成功～');
        }else {
            ui.success('保存失败～');
        }
    });
}
</script>
{__CONTENT__}
</body>
</html>