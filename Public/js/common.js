var librao = {};

librao.ajax = {
    post: function(url, data, success, data_type){
        data_type = data_type || 'json';
        data += '&ajax=1';
        $.ajax({
            type: 'post',
            url: url,
            cache: false,
            data: data,
            dataType: data_type,
            success: success,
            error: function(data){}
        });
    },
    get: function(url, success, data_type){
        data_type = data_type || 'html';
        $.ajax({
            type: 'get',
            cache: true,
            url: url,
            dataType: data_type,
            success: success,
            error: function(data){}
        });
    }
};

function doAction(){
	alert('操作成功');
}

//收藏操作
function updateLike(type, id){
	var data = "type="+type+'&id='+id;
	librao.ajax.post('/Index/updateLike',data,function(json){
		if(json.status){
			var countnum = $('#countnum_'+type).html();
			var countnum = parseInt(countnum) + parseInt(1);
			$('#countnum_'+type).html(countnum);
		}
	});
}