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
            data: data,
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
function addLike(nid,like){
	//alert(nid);
	var data = "n_id="+nid;
	librao.ajax.post('/Index/addLike',data, function(data){
		if(data.status){
			alert('操作成功！');
			$('#like_num').html(like+1);
		}else
			//Boxy.alert(data.info, null, {title: '提示信息'});
			alert(data.info);
	});
}