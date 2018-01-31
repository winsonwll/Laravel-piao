/**
 * Created by Winson on 2017/1/31 0031.
 */
var win = window,
    doc = document;

//缓存接口的url地址
//var URL = 'http://app.piaobuyer.com:8001/admin/',
var URL = 'http://piao.com/admin/',
    PORT = {
        doFrozenShow: URL+'show/frozen',    //冻结演出
        doThawShow: URL+'show/thaw',        //解冻演出
        doDestroyShow: URL+'show/',          //删除演出
        getVenue: URL+'getVenue/',          //获取指定城市的场馆
        doCreateShow: URL+'show',          //添加演出
        doUpdateShowInfo: URL+'show/',          //修改演出信息
        doDeleteShowPrice: URL+'show/deleteShowPrice/',          //删除演出票面价
        doUpdateShowPrice: URL+'show/updateShowPrice/',          //修改演出票面价
        doStoreShowPrice: URL+'show/storeShowPrice/',          //添加演出票面价
        doDeleteShowTime: URL+'show/deleteShowTime/',          //删除演出场次
        
        doDestroyVenue: URL+'venue/',        //删除场馆
        doUpdateVenue: URL+'venue/',         //更新场馆
        doCreateVenue: URL+'venue',         //添加场馆
        doCreateCity: URL+'city',         //添加城市

        doFrozenUser: URL+'user/frozen',    //冻结卖家
        doThawUser: URL+'user/thaw',        //解冻卖家
        getShowTime: URL+'user/getShowTime/',    //代挂单 获取指定演出的场次
        getShowPrice: URL+'user/getShowPrice/',    //代挂单 获取指定演出场次的票价
        doProxyOrder: URL+'user/proxy',    //代挂单

        doUpdateOrder: URL+'order/',         //更新挂单
        doOnSellOrder: URL+'order/onSell',    //上架挂单
        doOffSellOrder: URL+'order/offSell',   //下架挂单

        doCreateAdmin: URL+'admin',         //添加管理员
        doUpdateAdmin: URL+'admin/'         //更新管理员信息
    };

//模态提示窗
function fnModal($title, $msg, $flag){
    $(doc).find('#J-modal').remove();

    var $class = arguments[3] ? arguments[3] : '';
    var tpl = '<div class="modal fade" id="J-modal">' +
        '<div class="modal-dialog '+$class+'">' +
        '<div class="modal-content">' +
        '<div class="modal-header">' +
        '<button type="button" class="close" data-dismiss="modal">' +
        '<span aria-hidden="true">&times;</span>' +
        '</button>' +
        '<h4 class="modal-title">' + $title + '</h4>' +
        '</div>' +
        '<div class="modal-body">' + $msg + '</div>';
    if($flag){
        tpl += '<div class="modal-footer">' +
            '<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>' +
            '<button type="button" class="btn btn-primary J-confirm">确定</button>' +
            '</div>';
    }
    tpl += '</div>' +
        '</div>' +
        '</div>';
    $('body').append(tpl);

    $(doc).find('#J-modal').modal('show');
}

//演出管理
var Show = (function(){
    //列表页
    var $frozen = $('.J-frozen'),   //冻结按钮
        $thaw = $('.J-thaw'),       //解冻按钮
        $remove = $('.J-remove'),   //删除按钮
        $tips = $('#J-tips');       //提示

    //详情页
    var $Jfrozen = $('#J-frozen'),  //冻结按钮
        $Jthaw = $('#J-thaw'),      //解冻按钮
        $Jremove = $('#J-remove');   //删除按钮

    //添加演出
    var $createForm = $('#J-create-form'),                        //创建演出表单
        $showName = '',         //演出名称
        $file = '',             //演出封面
        $thumbnails = '',
        $performer = '',        //表演者
        $note1 = '',            //演出类型
        $cityName = '',         //演出城市
        $placeId = '',          //演出场馆
        $status = '',          //是否立即在前端显示
        $showTimePrice = [],         //演出场次和票面价
        $createBtn = $createForm.find('button[type=submit]'),     //创建按钮
        $JcityName = $('#J-cityName'),  //演出城市
        $Jvenue = $('#J-venue'),        //演出场馆
        $msg = {                        //提示信息
            0: '演出名称不能为空！',
            1: '演出城市不能为空！',
            2: '演出场馆不能为空！',
            3: '演出场次不能为空！',
            4: '票面价不能为空！',
            5: '演出封面不能为空！'
        };

    //修改演出信息
    var $updateShowInfoForm = $('#J-update-showInfo-form'),                        //修改演出信息表单
        $updateShowInfoBtn = $updateShowInfoForm.find('button[type=submit]');      //修改演出信息按钮

    //修改场次和票面价
    var $dataId = 0;
    
    return {
        init: function () {
            //事件
            this.bindEvents();
            //演出封面
            this.uploadShowPhoto();
        },
        //事件
        bindEvents: function (){
            var self = this,
                $obj = '',
                $id = '',
                $btnMsg = '',
                $iFlag = 0;

            var $editAreaNameVal = '',
                $editAreaPriceVal = '',
                $editAreaList = '';

            //列表页冻结
            $frozen.click(function () {
                fnModal('温馨提示', "确定要冻结该演出吗？", true, 'modal-sm');

                $obj = $(this);
                $id = $obj.attr('data-id');
                $btnMsg = '冻结中';
                $iFlag = 1;
            });
            //列表页解冻
            $thaw.click(function () {
                fnModal('温馨提示', "确定要解冻该演出吗？", true, 'modal-sm');

                $obj = $(this);
                $id = $obj.attr('data-id');
                $btnMsg = '解冻中';
                $iFlag = 5;
            });

            //详情页冻结
            $Jfrozen.click(function () {
                fnModal('温馨提示', "确定要冻结该演出吗？", true, 'modal-sm');

                $obj = $(this);
                $id = $obj.attr('data-id');
                $btnMsg = '冻结中';
                $iFlag = 3;
            });
            //详情页解冻
            $Jthaw.click(function () {
                fnModal('温馨提示', "确定要解冻该演出吗？", true, 'modal-sm');

                $obj = $(this);
                $id = $obj.attr('data-id');
                $btnMsg = '解冻中';
                $iFlag = 6;
            });

            //列表页删除
            $remove.click(function () {
                fnModal('温馨提示', "确定要删除该演出吗？", true, 'modal-sm');

                $obj = $(this);
                $id = $obj.attr('data-id');
                $btnMsg = '删除中';
                $iFlag = 2;
            });
            //详情页删除
            $Jremove.click(function () {
                fnModal('温馨提示', "确定要删除该演出吗？", true, 'modal-sm');

                $obj = $(this);
                $id = $obj.attr('data-id');
                $btnMsg = '删除中';
                $iFlag = 4;
            });

            //确定
            $(doc).on('click', '.J-confirm', function () {
                if (!$iFlag) {
                    $tips.html('非法操作');
                    $tips.parent().show();
                    return;
                }

                var _this = $(this);

                _this.hide();
                _this.after('<button class="btn btn-primary" type="button" disabled>' + $btnMsg + '</button>');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                switch ($iFlag) {
                    case 1: //确定冻结
                        $.ajax({
                            type: 'POST',
                            url: PORT.doFrozenShow,
                            data: {
                                id: $id
                            },
                            dataType: 'json',
                            success: function (data) {
                                if (data.status == 1) {
                                    $obj.parents('tr').find('.J-status').html('已冻结');
                                    $obj.parent('li').remove();
                                }
                                $(doc).find('#J-modal').modal('hide');
                                $tips.html(data.msg).parent().show();
                            }
                        });
                        break;
                    case 2: //确定删除
                        $.ajax({
                            type: 'DELETE',
                            url: PORT.doDestroyShow+$id,
                            dataType: 'json',
                            success: function (data) {
                                if (data.status == 1) {
                                    $obj.parents('tr').remove();
                                }
                                $(doc).find('#J-modal').modal('hide');
                                $tips.html(data.msg).parent().show();
                            }
                        });
                        break;
                    case 3: //确定冻结
                        $.ajax({
                            type: 'POST',
                            url: PORT.doFrozenShow,
                            data: {
                                id: $id
                            },
                            dataType: 'json',
                            success: function (data) {
                                if (data.status == 1) {
                                    $(doc).find('.J-status').html('已冻结');
                                    $obj.after('<button class="btn btn-default btn-lg" type="button" disabled>已冻结</button>');
                                    $obj.remove();
                                }
                                $(doc).find('#J-modal').modal('hide');
                                $tips.html(data.msg).parent().show();
                            }
                        });
                        break;
                    case 4: //确定删除
                        $.ajax({
                            type: 'DELETE',
                            url: PORT.doDestroyShow+$id,
                            dataType: 'json',
                            success: function (data) {
                                if (data.status == 1) {
                                    setTimeout(function () {
                                        win.location.href=URL+'show';
                                    },300)
                                }
                                $(doc).find('#J-modal').modal('hide');
                                $tips.html(data.msg).parent().show();
                            }
                        });
                        break;
                    case 5: //确定解冻
                        $.ajax({
                            type: 'POST',
                            url: PORT.doThawShow,
                            data: {
                                id: $id
                            },
                            dataType: 'json',
                            success: function (data) {
                                if (data.status == 1) {
                                    $obj.parents('tr').find('.J-status').html('已解冻');
                                    $obj.parent('li').remove();
                                }
                                $(doc).find('#J-modal').modal('hide');
                                $tips.html(data.msg).parent().show();
                            }
                        });
                        break;
                    case 6: //确定解冻
                        $.ajax({
                            type: 'POST',
                            url: PORT.doThawShow,
                            data: {
                                id: $id
                            },
                            dataType: 'json',
                            success: function (data) {
                                if (data.status == 1) {
                                    $(doc).find('.J-status').html('已解冻');
                                    $obj.after('<button class="btn btn-default btn-lg" type="button" disabled>已解冻</button>');
                                    $obj.remove();
                                }
                                $(doc).find('#J-modal').modal('hide');
                                $tips.html(data.msg).parent().show();
                            }
                        });
                        break;
                    case 7: //添加票面价提示
                        $(doc).find('#J-modal').modal('hide');
                        break;
                    case 8: //确定删除票面价
                        $.ajax({
                            type: 'POST',
                            url: PORT.doDeleteShowPrice+$id,
                            dataType: 'json',
                            success: function (data) {
                                if (data.status == 1) {
                                    $obj.parents('tr').remove();
                                }
                                $(doc).find('#J-modal').modal('hide');
                                $tips.html(data.msg).parent().show();
                            }
                        });
                        break;
                    case 9: //确定修改票面价
                        $.ajax({
                            type: 'POST',
                            url: PORT.doUpdateShowPrice+$id,
                            data: {
                                AreaName: $editAreaNameVal,
                                AreaPrice: $editAreaPriceVal
                            },
                            dataType: 'json',
                            success: function (data) {
                                if (data.status == 1) {
                                    $obj.hide();
                                    $obj.prev().show();
                                }
                                $(doc).find('#J-modal').modal('hide');
                                $tips.html(data.msg).parent().show();
                            }
                        });
                        break;
                    case 10: //确定添加票面价
                        $.ajax({
                            type: 'POST',
                            url: PORT.doStoreShowPrice+$id,
                            data: {
                                AreaName: $editAreaNameVal,
                                AreaPrice: $editAreaPriceVal
                            },
                            dataType: 'json',
                            success: function (data) {
                                if (data.status == 1) {
                                    var html = '<tr data-showPriceId="'+data.data+'">' +
                                        '<td><input name="AreaName" class="form-control" type="text" value="'+$.trim($editAreaNameVal)+'" placeholder="请输入票面价名称"></td>'+
                                        '<td><input name="AreaPrice" class="form-control" type="text" value="'+$.trim($editAreaPriceVal)+'" placeholder="请输入票面价"></td>'+
                                        '<td>' +
                                        '<button class="btn btn-default J-delete-showPrice" type="button">删除</button>' +
                                        '<button class="btn btn-success J-edit-showPrice" type="button">修改</button>' +
                                        '</td>'+
                                        '</tr>';
                                    $editAreaList.append(html);

                                    $obj.parents('tr').find('input[name=AreaName]').val('');
                                    $obj.parents('tr').find('input[name=AreaPrice]').val('');
                                }
                                $(doc).find('#J-modal').modal('hide');
                                $tips.html(data.msg).parent().show();
                            }
                        });
                        break;
                    case 11: //确定删除场次
                        $.ajax({
                            type: 'POST',
                            url: PORT.doDeleteShowTime+$id,
                            dataType: 'json',
                            success: function (data) {
                                if (data.status == 1) {
                                    $obj.parents('.form-group').remove();
                                }
                                $(doc).find('#J-modal').modal('hide');
                                $tips.html(data.msg).parent().show();
                            }
                        });
                        break;
                }
            });

            //选择演出场馆
            $JcityName.on('change', function () {
                var _this = $(this),
                    $cityName = _this.val();
                self.changeVenue($cityName);
            });
            
            //添加票面价
            $(doc).on('click', '.J-add-showPrice', function () {
                var _this = $(this);
                var $areaNameVal = $.trim(_this.parents('tr').find('input[name=AreaName]').val()),
                    $areaPriceVal = $.trim(_this.parents('tr').find('input[name=AreaPrice]').val()),
                    $areaList = _this.parents('.form-group').find('tbody');
                
                if($areaNameVal.length==0 || $areaPriceVal.length==0){
                    fnModal('温馨提示', "票面价名称和票面价不能为空！", true, 'modal-sm');
                    $iFlag = 7;
                }else{
                    if($areaPriceVal >= 1){
                        var reg = /^\d+(\.\d{2})?$/;
                        if(reg.test($areaPriceVal)){
                            if(_this.next().val().length>0){
                                var $arrAreaName=JSON.parse(_this.next().val()).AreaName,
                                    $arrAreaPrice=JSON.parse(_this.next().val()).AreaPrice,
                                    $arrShowPriceId=JSON.parse(_this.next().val()).ShowPriceId;
                            }else{
                                var $arrAreaName=[],
                                    $arrAreaPrice=[],
                                    $arrShowPriceId=[];
                            }

                            if($.inArray(Number($areaPriceVal).toFixed(2), $arrAreaPrice)===-1){
                                $dataId++;
                                var html = '<tr data-showPriceId="0'+$dataId+'">' +
                                    '<td><input name="AreaName" class="form-control" type="text" value="'+$.trim($areaNameVal)+'" placeholder="请输入票面价名称"></td>'+
                                    '<td><input name="AreaPrice" class="form-control" type="text" value="'+$.trim($areaPriceVal)+'" placeholder="请输入票面价"></td>'+
                                    '<td><button class="btn btn-default J-remove-showPrice" type="button">删除</button></td>'+
                                    '</tr>';
                                $areaList.append(html);
                                _this.parents('tr').find('input[name=AreaName]').val('');
                                _this.parents('tr').find('input[name=AreaPrice]').val('');

                                $arrAreaName.push($areaNameVal);
                                $arrAreaPrice.push($areaPriceVal);
                                $arrShowPriceId.push('0'+$dataId);

                                var $arrShowPrice={
                                    ShowTimeId: _this.parents('.form-group').find('label').attr('data-showtimeid'),
                                    ShowTime: _this.parents('.form-group').find('label').html(),
                                    ShowPriceId: $arrShowPriceId,
                                    AreaName: $arrAreaName,
                                    AreaPrice: $arrAreaPrice
                                };

                                _this.next().val(JSON.stringify($arrShowPrice));
                            }else{
                                fnModal('温馨提示', "该场次下的票面价已经存在！", true, 'modal-sm');
                                $iFlag = 7;
                            }
                        }else{
                            fnModal('温馨提示', "票面价只能输入数字和小数点、且小数点后只能为2位数！", true, 'modal-sm');
                            $iFlag = 7;
                        }
                    }else{
                        fnModal('温馨提示', "票面价最小为1元！", true, 'modal-sm');
                        $iFlag = 7;
                    }
                }
            });

            //删除票面价
            $(doc).on('click', '.J-remove-showPrice', function () {
                var _this = $(this);
                var $arrAreaName=JSON.parse(_this.parents('.form-group').find('input[type=hidden]').val()).AreaName,
                    $arrAreaPrice=JSON.parse(_this.parents('.form-group').find('input[type=hidden]').val()).AreaPrice,
                    $arrShowPriceId=JSON.parse(_this.parents('.form-group').find('input[type=hidden]').val()).ShowPriceId;

                var $areaNameVal = $.trim(_this.parents('tr').find('input[name=AreaName]').val()),
                    $areaPriceVal = $.trim(_this.parents('tr').find('input[name=AreaPrice]').val()),
                    $arrShowPriceIdVal = $.trim(_this.parents('tr').attr('data-showpriceid'));

                var $index1 = $.inArray($areaNameVal, $arrAreaName),
                    $index2 = $.inArray($areaPriceVal, $arrAreaPrice),
                    $index3 = $.inArray($arrShowPriceIdVal, $arrShowPriceId);

                if($index1 !== -1 || $index2 !== -1 || $index3 !== -1){
                    $arrAreaName.splice($index1, 1);
                    $arrAreaPrice.splice($index2, 1);
                    $arrShowPriceId.splice($index3, 1);
                }

                var $arrShowPrice={
                    ShowTimeId: _this.parents('.form-group').find('label').attr('data-showtimeid'),
                    ShowTime: _this.parents('.form-group').find('label').html(),
                    ShowPriceId: $arrShowPriceId,
                    AreaName: $arrAreaName,
                    AreaPrice: $arrAreaPrice
                };

                _this.parents('.form-group').find('input[type=hidden]').val(JSON.stringify($arrShowPrice));
                _this.parents('tr').remove();
            });

            //执行添加演出
            $createBtn.on('click', function(){
                var _this = $(this);

                $showName = $createForm.find('input[name=ShowName]'),         //演出名称
                $file = $createForm.find('input[name=Photo]'),                //演出封面
                $performer = $createForm.find('input[name=Performer]'),        //表演者
                $note1 = $createForm.find('select[name=Note1]'),                //演出类型
                $cityName = $createForm.find('select[name=CityName]'),         //演出城市
                $placeId = $createForm.find('select[name=PlaceId]'),          //演出场馆
                $status = $createForm.find('input[name=Status]');            //是否立即在前端显示

                var $showNameVal = $.trim($showName.val()),
                    $performerVal = $.trim($performer.val()),
                    $note1Val = $.trim($note1.val()),
                    $cityNameVal = $.trim($cityName.val()),
                    $placeIdVal = $.trim($createForm.find('select[name=PlaceId] option:selected').val()),
                    $statusVal = $createForm.find('input[name=Status]:checked').val();

                if(self.checkShowName() && self.checkPhoto() && self.checkCity() && self.checkVenue() && self.checkShowTime() && self.checkShowPrice()){
                    $.each($addShowTime.find('input[type=hidden]'), function(i, n){
                        if($(n).val()){
                            $showTimePrice.push($(n).val());
                        }
                    });

                    var formdata=new FormData($createForm[0]);
                    formdata.append("ShowName" , $showNameVal);
                    formdata.append("file" , $file[0].files[0]);
                    formdata.append("Performer" , $performerVal);
                    formdata.append("Note1" , $note1Val);
                    formdata.append("CityName" , $cityNameVal);
                    formdata.append("PlaceId" , $placeIdVal);
                    formdata.append("Status" , $statusVal);
                    formdata.append("Note" , JSON.stringify($showTimePrice));

                    _this.hide();
                    _this.after('<button class="btn btn-success btn-lg" type="button" disabled>添加中...</button>');

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'POST',
                        url: PORT.doCreateShow,
                        data : formdata,
                        cache : false,
                        processData : false, // 不处理发送的数据，因为data值是Formdata对象，不需要对数据做处理
                        contentType : false, // 不设置Content-type请求头
                        success: function (data) {
                            if(data.status == 1){
                                setTimeout(function () {
                                    win.location.href=URL+'show';
                                },300)
                            }
                            $tips.html(data.msg).parent().show();
                            _this.show();
                            _this.next().remove();
                        }
                    });
                }
                return false;
            });

            //执行修改演出信息
            $updateShowInfoBtn.on('click', function(){
                var _this = $(this);

                    $showName = $updateShowInfoForm.find('input[name=ShowName]'),         //演出名称
                    $file = $updateShowInfoForm.find('input[name=Photo]'),                //演出封面
                    $thumbnails = $updateShowInfoForm.find('.file-preview-thumbnails'),                //演出封面
                    $performer = $updateShowInfoForm.find('input[name=Performer]'),        //表演者
                    $note1 = $updateShowInfoForm.find('select[name=Note1]'),                //演出类型
                    $cityName = $updateShowInfoForm.find('select[name=CityName]'),         //演出城市
                    $placeId = $updateShowInfoForm.find('select[name=PlaceId]'),          //演出场馆
                    $status = $updateShowInfoForm.find('input[name=Status]');            //是否立即在前端显示

                var $showNameVal = $.trim($showName.val()),
                    $performerVal = $.trim($performer.val()),
                    $note1Val = $.trim($note1.val()),
                    $cityNameVal = $.trim($cityName.val()),
                    $placeIdVal = $.trim($updateShowInfoForm.find('select[name=PlaceId] option:selected').val()),
                    $statusVal = $updateShowInfoForm.find('input[name=Status]:checked').val();

                if(self.checkShowName() && self.checkUpdatePhoto() && self.checkCity() && self.checkVenue()){
                    var formdata=new FormData($updateShowInfoForm[0]);
                    formdata.append("ShowName", $showNameVal);
                    formdata.append("file", $file[0].files[0]);
                    formdata.append("Performer", $performerVal);
                    formdata.append("Note1", $note1Val);
                    formdata.append("CityName", $cityNameVal);
                    formdata.append("PlaceId", $placeIdVal);
                    formdata.append("Status" , $statusVal);

                    _this.hide();
                    _this.after('<button class="btn btn-success btn-lg" type="button" disabled>修改中...</button>');

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'POST',
                        url: PORT.doUpdateShowInfo+_this.attr('data-id'),
                        data : formdata,
                        cache : false,
                        processData : false, // 不处理发送的数据，因为data值是Formdata对象，不需要对数据做处理
                        contentType : false, // 不设置Content-type请求头
                        success: function (data) {
                            if(data.status == 1){
                                setTimeout(function () {
                                    win.location.href=URL+'show/'+_this.attr('data-id');
                                },300)
                            }
                            $tips.html(data.msg).parent().show();
                            _this.show();
                            _this.next().remove();
                        }
                    });
                }
                return false;
            });

            //执行删除演出票面价
            $(doc).on('click', '.J-delete-showPrice', function () {
                fnModal('温馨提示', "确定要删除该票面价吗？", true, 'modal-sm');

                $obj = $(this);
                $id = $obj.parents('tr').attr('data-showpriceid');
                $btnMsg = '删除中';
                $iFlag = 8;
            });

            //执行修改票面价
            $(doc).on('input', 'input[name=AreaName], input[name=AreaPrice]', function () {
               $(this).parents('tr').find('.J-delete-showPrice').hide();
               $(this).parents('tr').find('.J-edit-showPrice').css('display','block');
            });
            $(doc).on('click', '.J-edit-showPrice', function () {
                $obj = $(this);

                    $editAreaNameVal = $.trim($obj.parents('tr').find('input[name=AreaName]').val()),
                    $editAreaPriceVal = $.trim($obj.parents('tr').find('input[name=AreaPrice]').val());

                if($editAreaNameVal.length==0 || $editAreaPriceVal.length==0){
                    fnModal('温馨提示', "票面价名称和票面价不能为空！", true, 'modal-sm');
                    $iFlag = 7;
                }else{
                    if($editAreaPriceVal >= 1){
                        var reg = /^\d+(\.\d{2})?$/;
                        if(reg.test($editAreaPriceVal)){
                            fnModal('温馨提示', "确定要修改该票面价吗？", true, 'modal-sm');

                            $id = $obj.parents('tr').attr('data-showpriceid');
                            $btnMsg = '修改中';
                            $iFlag = 9;
                        }else{
                            fnModal('温馨提示', "票面价只能输入数字和小数点、且小数点后只能为2位数！", true, 'modal-sm');
                            $iFlag = 7;
                        }
                    }else{
                        fnModal('温馨提示', "票面价最小为1元！", true, 'modal-sm');
                        $iFlag = 7;
                    }
                }
            });

            //执行添加票面价
            $(doc).on('click', '.J-add-Area', function () {
                $obj = $(this);

                    $editAreaNameVal = $.trim($obj.parents('tr').find('input[name=AreaName]').val()),
                    $editAreaPriceVal = $.trim($obj.parents('tr').find('input[name=AreaPrice]').val()),
                    $editAreaList = $obj.parents('.form-group').find('tbody');

                if($editAreaNameVal.length==0 || $editAreaPriceVal.length==0){
                    fnModal('温馨提示', "票面价名称和票面价不能为空！", true, 'modal-sm');
                    $iFlag = 7;
                }else{
                    if($editAreaPriceVal >= 1){
                        var reg = /^\d+(\.\d{2})?$/;
                        if(reg.test($editAreaPriceVal)){
                            fnModal('温馨提示', "确定要修改该票面价吗？", true, 'modal-sm');

                            $id = $obj.parents('.form-group').find('label').attr('data-showtimeid');
                            $btnMsg = '添加中';
                            $iFlag = 10;
                        }else{
                            fnModal('温馨提示', "票面价只能输入数字和小数点、且小数点后只能为2位数！", true, 'modal-sm');
                            $iFlag = 7;
                        }
                    }else{
                        fnModal('温馨提示', "票面价最小为1元！", true, 'modal-sm');
                        $iFlag = 7;
                    }
                }
            });

            //执行删除演出场次
            $(doc).on('click', '.J-delete-showTime', function () {
                fnModal('温馨提示', "确定要删除该场次吗？", true, 'modal-sm');

                $obj = $(this);
                $id = $obj.attr('data-showTimeId');
                $btnMsg = '删除中';
                $iFlag = 11;
            });

        },
        //选择演出场馆
        changeVenue: function ($cityName) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'GET',
                url: PORT.getVenue+$cityName,
                dataType: 'json',
                success: function (data) {
                    if (data.status == 1) {
                        var html = '',
                            $data = data.data;
                        for(var i=0,len=$data.length;i<len;i++){
                            html+='<option value="'+$data[i].id+'">'+$data[i].Name+'</option>'
                        }
                        $Jvenue.html(html);
                        $Jvenue.selectpicker('refresh');
                    }
                }
            });
        },
        //检测演出名称
        checkShowName: function () {
            var $val = $showName.val();

            if($.trim($val).length==0){
                $tips.html($msg[0]).parent().show();
                return false;
            }
            return true;
        },
        //检测添加演出封面
        checkPhoto: function () {
            var $val = $file.val();

            if($.trim($val).length==0){
                $tips.html($msg[5]).parent().show();
                return false;
            }
            return true;
        },
        //检测更新演出封面
        checkUpdatePhoto: function () {
            var $val = $thumbnails.html();

            if($.trim($val).length==0){
                $tips.html($msg[5]).parent().show();
                return false;
            }
            return true;
        },
        //检测演出城市
        checkCity: function(){
            var $val = $cityName.val();

            if($.trim($val).length==0){
                $tips.html($msg[1]).parent().show();
                return false;
            }
            return true;
        },
        //检测演出场馆
        checkVenue: function(){
            var $val = $placeId.val();

            if($.trim($val).length==0){
                $tips.html($msg[2]).parent().show();
                return false;
            }
            return true;
        },
        //检测演出场次
        checkShowTime: function () {
            if(!iFlag_showTime){
                $tips.html($msg[3]).parent().show();
                return false;
            }
            return true;
        },
        //检测票面价
        checkShowPrice: function () {
            var $arr=$addShowTime.find('input[type=hidden]');
            var $arr2=[];

            $.each($arr, function(i, n){
                if($(n).val().length>0){
                    $arr2.push($(n).val());
                }
            });

            if($arr2.length==0){
                $tips.html($msg[4]).parent().show();
                return false;
            }
            return true;
        },
        //演出封面
        uploadShowPhoto: function () {
            var projectfileoptions = {
                showUpload : false,
                showRemove : false,
                language : 'zh',
                allowedPreviewTypes : [ 'image' ],
                allowedFileExtensions : [ 'jpg', 'png', 'gif' ],
                maxFileSize : 2000
            };

            // 文件上传框
            $('input[class=projectfile]').each(function() {
                var imageurl = $(this).attr("value");

                if (imageurl){
                    var op = $.extend({ // 预览图片的设置
                        initialPreview: ["<img src='" + imageurl + "' class='file-preview-image'>"]
                    }, projectfileoptions);

                    $(this).fileinput(op);
                } else {
                    $(this).fileinput(projectfileoptions);
                }
            });
        }
    }
})();

//场馆管理
var Venue = (function () {
    //列表页
    var $remove = $('.J-remove'),   //删除按钮
        $tips = $('#J-tips');       //提示

    //详情页
    var $Jremove = $('#J-remove');   //删除按钮

    //修改页
    var $updateForm = $('#J-update-form'),                      //修改场馆表单
        $venueName = '',       //场馆名称
        $cityName = '',    //所在城市
        $address = '',       //场馆地址
        $introduce = '',   //场馆简介
        $updateBtn = $updateForm.find('button[type=submit]'),     //修改按钮
        $msg = {                                                  //提示信息
            0: '场馆名称不能为空！',
            1: '场馆所在城市不能为空！',
            2: '场馆地址不能为空！',
            3: '城市名称不能为空！'
        };

    //添加场馆页
    var $createForm = $('#J-create-form'),                        //创建场馆表单
        $createBtn = $createForm.find('button[type=submit]');     //创建按钮

    //添加城市页
    var $createCity = $('#J-create-city'),                        //创建城市表单
        $createCityBtn = $createCity.find('button[type=submit]');     //创建按钮

    return {
        init: function () {
            //事件
            this.bindEvents();
        },
        //事件
        bindEvents: function (){
            var self = this,
                $obj = '',
                $id = '',
                $btnMsg = '',
                $iFlag = 0;

            //列表页删除
            $remove.click(function () {
                fnModal('温馨提示', "确定要删除该场馆吗？", true, 'modal-sm');

                $obj = $(this);
                $id = $obj.attr('data-id');
                $btnMsg = '删除中';
                $iFlag = 1;
            });

            //详情页删除
            $Jremove.click(function () {
                fnModal('温馨提示', "确定要删除该场馆吗？", true, 'modal-sm');

                $obj = $(this);
                $id = $obj.attr('data-id');
                $btnMsg = '删除中';
                $iFlag = 2;
            });

            //确定
            $(doc).on('click', '.J-confirm', function () {
                if (!$iFlag) {
                    $tips.html('非法操作');
                    $tips.parent().show();
                    return;
                }

                var _this = $(this);

                _this.hide();
                _this.after('<button class="btn btn-primary" type="button" disabled>' + $btnMsg + '</button>');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                switch ($iFlag) {
                    case 1: //确定删除
                        $.ajax({
                            type: 'DELETE',
                            url: PORT.doDestroyVenue+$id,
                            dataType: 'json',
                            success: function (data) {
                                if (data.status == 1) {
                                    $obj.parents('tr').remove();
                                }
                                $(doc).find('#J-modal').modal('hide');
                                $tips.html(data.msg).parent().show();
                            }
                        });
                        break;
                    case 2: //确定删除
                        $.ajax({
                            type: 'DELETE',
                            url: PORT.doDestroyVenue+$id,
                            dataType: 'json',
                            success: function (data) {
                                if (data.status == 1) {
                                    setTimeout(function () {
                                        win.location.href=URL+'venue';
                                    },300)
                                }
                                $(doc).find('#J-modal').modal('hide');
                                $tips.html(data.msg).parent().show();
                            }
                        });
                        break;
                }
            });

            //执行修改场馆信息
            $updateBtn.on('click', function(){
                var _this = $(this),
                    $id = _this.attr('data-id');

                    $venueName = $updateForm.find('input[name=Name]'),       //场馆名称
                    $cityName = $updateForm.find('select[name=CityName]'),    //所在城市
                    $address = $updateForm.find('input[name=Address]'),       //场馆地址
                    $introduce = $updateForm.find('textarea[name=Introduce]');   //场馆简介

                var $venueNameVal = $.trim($venueName.val()),
                    $cityNameVal = $.trim($cityName.val()),
                    $addressVal = $.trim($address.val()),
                    $introduceVal = $.trim($introduce.val());

                if(self.checkVenueName() && self.checkCityName() && self.checkAaddress()){
                    _this.hide();
                    _this.after('<button class="btn btn-success btn-lg" type="button" disabled>修改中...</button>');

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'PUT',
                        url: PORT.doUpdateVenue+$id,
                        data: {
                            Name: $venueNameVal,
                            CityName: $cityNameVal,
                            Address: $addressVal,
                            Introduce: $introduceVal
                        },
                        dataType: 'json',
                        success: function (data) {
                            if(data.status == 1){
                                setTimeout(function () {
                                    win.location.href=URL+'venue/'+$id;
                                },300)
                            }
                            $tips.html(data.msg).parent().show();
                            _this.show();
                            _this.next().remove();
                        }
                    });
                }
                return false;
            });

            //执行添加场馆信息
            $createBtn.on('click', function(){
                var _this = $(this);

                $venueName = $createForm.find('input[name=Name]'),       //场馆名称
                    $cityName = $createForm.find('select[name=CityName]'),    //所在城市
                    $address = $createForm.find('input[name=Address]'),       //场馆地址
                    $introduce = $createForm.find('textarea[name=Introduce]');   //场馆简介

                var $venueNameVal = $.trim($venueName.val()),
                    $cityNameVal = $.trim($cityName.val()),
                    $addressVal = $.trim($address.val()),
                    $introduceVal = $.trim($introduce.val());

                if(self.checkVenueName() && self.checkCityName() && self.checkAaddress()){
                    _this.hide();
                    _this.after('<button class="btn btn-success btn-lg" type="button" disabled>添加中...</button>');

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'POST',
                        url: PORT.doCreateVenue,
                        data: {
                            Name: $venueNameVal,
                            CityName: $cityNameVal,
                            Address: $addressVal,
                            Introduce: $introduceVal
                        },
                        dataType: 'json',
                        success: function (data) {
                            if(data.status == 1){
                                setTimeout(function () {
                                    win.location.href=URL+'venue';
                                },300)
                            }
                            $tips.html(data.msg).parent().show();
                            _this.show();
                            _this.next().remove();
                        }
                    });
                }
                return false;
            });

            //执行添加城市信息
            $createCityBtn.on('click', function(){
                var _this = $(this);

                $cityName = $createCity.find('input[name=CityName]');    //城市

                var $cityNameVal = $.trim($cityName.val());

                if(self.checkCity()){
                    _this.hide();
                    _this.after('<button class="btn btn-success btn-lg" type="button" disabled>添加中...</button>');

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'POST',
                        url: PORT.doCreateCity,
                        data: {
                            CityName: $cityNameVal
                        },
                        dataType: 'json',
                        success: function (data) {
                            if(data.status == 1){
                                setTimeout(function () {
                                    win.location.href=URL+'venue/create';
                                },300)
                            }
                            $tips.html(data.msg).parent().show();
                            _this.show();
                            _this.next().remove();
                        }
                    });
                }
                return false;
            });

        },
        //检测场馆名称
        checkVenueName: function(){
            var $val = $venueName.val();

            if($.trim($val).length==0){
                $tips.html($msg[0]).parent().show();
                return false;
            }
            return true;
        },
        //检测场馆所在城市
        checkCityName: function(){
            var $val = $cityName.find('option:selected').val();

            if($.trim($val).length==0){
                $tips.html($msg[1]).parent().show();
                return false;
            }
            return true;
        },
        //检测场馆地址
        checkAaddress: function(){
            var $val = $address.val();

            if($.trim($val).length==0){
                $tips.html($msg[2]).parent().show();
                return false;
            }
            return true;
        },
        //检测城市
        checkCity: function(){
            var $val = $cityName.val();

            if($.trim($val).length==0){
                $tips.html($msg[3]).parent().show();
                return false;
            }
            return true;
        }
    }
})();

//卖家管理
var User = (function () {
    //列表页
    var $frozen = $('.J-frozen'),   //冻结按钮
        $thaw = $('.J-thaw'),       //解冻按钮
        $proxy = $('.J-proxy'),     //代挂按钮
        $tips = $('#J-tips');       //提示

    //详情页
    var $Jfrozen = $('#J-frozen'),  //冻结按钮
        $Jthaw = $('#J-thaw'),      //解冻按钮
        $Jproxy = $('#J-proxy');    //代挂按钮

    //代挂单
    var $proxyTpl = $('#J-proxy-tpl'),     //代挂模板
        $modaltips = '',     //模态窗提示

        $showId = '',           //演出id
        $showTimeId = '',       //演出场次id
        $showPriceId = '',      //票面价id
        $perPrice = '',         //同行价
        $sellNum = '',          //出售数量
        $restDay = '',          //有效天数
        $msg = {                                                  //提示信息
            0: '演出名称不能为空！',
            1: '演出场次不能为空！',
            2: '票面价不能为空！',
            3: '同行价不能为空！',
            4: '出售数量不能为空！',
            5: '有效天数不能为空！',
            6: '同行价最小为1元！',
            7: '出售数量最少为1！',
            8: '有效天数至少为1天！'
        };

    return {
        init: function () {
            //事件
            this.bindEvents();
        },
        //事件
        bindEvents: function (){
            var self = this,
                $obj = '',
                $id = '',
                $btnMsg = '',
                $iFlag = 0;

            //列表页冻结
            $frozen.click(function () {
                fnModal('温馨提示', "确定要冻结该卖家吗？", true, 'modal-sm');

                $obj = $(this);
                $id = $obj.attr('data-id');
                $btnMsg = '冻结中';
                $iFlag = 1;
            });
            //列表页解冻
            $thaw.click(function () {
                fnModal('温馨提示', "确定要解冻该卖家吗？", true, 'modal-sm');

                $obj = $(this);
                $id = $obj.attr('data-id');
                $btnMsg = '解冻中';
                $iFlag = 2;
            });
            //列表页代挂
            $proxy.click(function () {
                $obj = $(this);

                var html=$proxyTpl.html();
                fnModal('添加代挂单', html, true);

                $('#J-proxy-user').html($obj.parents('tr').find('.J-realname').html());
                $('.selectpicker').selectpicker();

                $id = $obj.attr('data-id');
                $btnMsg = '挂单中';
                $iFlag = 5;
            });

            $(doc).on('change', '#J-showName', function () {
                var _this = $(this);
                $showId = _this.val();

                self.changeShowTime($showId);
            });
            $(doc).on('change', '#J-showTime', function () {
                var _this = $(this);
                $showTimeId = _this.val();

                self.changeShowPrice($showTimeId);
            });

            //详情页冻结
            $Jfrozen.click(function () {
                fnModal('温馨提示', "确定要冻结该卖家吗？", true, 'modal-sm');

                $obj = $(this);
                $id = $obj.attr('data-id');
                $btnMsg = '冻结中';
                $iFlag = 3;
            });
            //详情页解冻
            $Jthaw.click(function () {
                fnModal('温馨提示', "确定要解冻该卖家吗？", true, 'modal-sm');

                $obj = $(this);
                $id = $obj.attr('data-id');
                $btnMsg = '解冻中';
                $iFlag = 4;
            });
            //详情页代挂
            $Jproxy.click(function () {
                $obj = $(this);

                var html=$proxyTpl.html();
                fnModal('添加代挂单', html, true);

                $('#J-proxy-user').html($(doc).find('.J-realname').html());
                $('.selectpicker').selectpicker();

                $id = $obj.attr('data-id');
                $btnMsg = '挂单中';
                $iFlag = 5;
            });

            //确定
            $(doc).on('click', '.J-confirm', function () {
                if (!$iFlag) {
                    $tips.html('非法操作');
                    $tips.parent().show();
                    return;
                }

                var _this = $(this);

                _this.hide();
                _this.after('<button class="btn btn-primary" type="button" disabled>' + $btnMsg + '</button>');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                switch ($iFlag) {
                    case 1: //确定冻结
                        $.ajax({
                            type: 'POST',
                            url: PORT.doFrozenUser,
                            data: {
                                id: $id
                            },
                            dataType: 'json',
                            success: function (data) {
                                if (data.status == 1) {
                                    $obj.parents('tr').find('.J-status').html('已冻结');
                                    $obj.remove();
                                }
                                $(doc).find('#J-modal').modal('hide');
                                $tips.html(data.msg).parent().show();
                            }
                        });
                        break;
                    case 2: //确定解冻
                        $.ajax({
                            type: 'POST',
                            url: PORT.doThawUser,
                            data: {
                                id: $id
                            },
                            dataType: 'json',
                            success: function (data) {
                                if (data.status == 1) {
                                    $obj.parents('tr').find('.J-status').html('已解冻');
                                    $obj.remove();
                                }
                                $(doc).find('#J-modal').modal('hide');
                                $tips.html(data.msg).parent().show();
                            }
                        });
                        break;
                    case 3: //确定冻结
                        $.ajax({
                            type: 'POST',
                            url: PORT.doFrozenUser,
                            data: {
                                id: $id
                            },
                            dataType: 'json',
                            success: function (data) {
                                if (data.status == 1) {
                                    $(doc).find('.J-status').html('已冻结');
                                    $obj.after('<button class="btn btn-default btn-lg" type="button" disabled>已冻结</button>');
                                    $obj.remove();
                                }
                                $(doc).find('#J-modal').modal('hide');
                                $tips.html(data.msg).parent().show();
                            }
                        });
                        break;
                    case 4: //确定解冻
                        $.ajax({
                            type: 'POST',
                            url: PORT.doThawUser,
                            data: {
                                id: $id
                            },
                            dataType: 'json',
                            success: function (data) {
                                if (data.status == 1) {
                                    $(doc).find('.J-status').html('已解冻');
                                    $obj.after('<button class="btn btn-default btn-lg" type="button" disabled>已解冻</button>');
                                    $obj.remove();
                                }
                                $(doc).find('#J-modal').modal('hide');
                                $tips.html(data.msg).parent().show();
                            }
                        });
                        break;
                    case 5: //确定代挂单
                        _this.show();
                        _this.next().remove();

                        $modaltips = $(doc).find('#J-modal-tips'),
                        $showId = $(doc).find('select[name=ShowId]'),           //演出id
                        $showTimeId = $(doc).find('select[name=ShowTimeId]'),   //演出场次id
                        $showPriceId = $(doc).find('select[name=ShowPriceId]'), //票面价id
                        $perPrice = $(doc).find('input[name=PerPrice]'),   //同行价
                        $sellNum = $(doc).find('input[name=SellNum]'),     //出售数量
                        $restDay = $(doc).find('select[name=restDay]');    //有效天数

                        var $showIdVal = $.trim($showId.val()),
                            $showTimeIdVal = $.trim($showTimeId.val()),
                            $showPriceIdVal = $.trim($showPriceId.val()),
                            $perPriceVal = $.trim($perPrice.val()),
                            $sellNumVal = $.trim($sellNum.val()),
                            $restDayVal = $.trim($restDay.val());

                        if(self.checkShowName() && self.checkShowTime() && self.checkShowPrice() && self.checkPerPrice() && self.checkSellNum() && self.checkRestDay()){
                            _this.hide();
                            _this.after('<button class="btn btn-primary" type="button" disabled>' + $btnMsg + '</button>');

                            $.ajax({
                                type: 'POST',
                                url: PORT.doProxyOrder,
                                data: {
                                    UserId: $id,
                                    ShowId: $showIdVal,
                                    ShowTimeId: $showTimeIdVal,
                                    ShowPriceId: $showPriceIdVal,
                                    PerPrice: $perPriceVal,
                                    SellNum: $sellNumVal,
                                    restDay: $restDayVal
                                },
                                dataType: 'json',
                                success: function (data) {
                                    if (data.status == 1) {
                                        $modaltips.html('代挂单成功').parent().show();
                                    }
                                    $(doc).find('#J-modal').modal('hide');
                                    $tips.html(data.msg).parent().show();
                                }
                            });
                        }
                        return false;
                        break;
                }
            });
        },
        changeShowTime: function ($id) {
            var self = this;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'GET',
                url: PORT.getShowTime+$id,
                dataType: 'json',
                success: function (data) {
                    if (data.status == 1) {
                        var html = '',
                            $data = data.data;
                        for(var i=0,len=$data.length;i<len;i++){
                            html+='<option value="'+$data[i].id+'">'+$data[i].ShowTime+'</option>'
                        }
                        $(doc).find('#J-showTime').html(html);

                        self.changeShowPrice($data[0].id);
                    }
                }
            });
        },
        changeShowPrice: function ($id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'GET',
                url: PORT.getShowPrice+$id,
                dataType: 'json',
                success: function (data) {
                    if (data.status == 1) {
                        var html = '',
                            $data = data.data;
                        for(var i=0,len=$data.length;i<len;i++){
                            html+='<option value="'+$data[i].id+'">'+$data[i].AreaName+'</option>'
                        }
                        $(doc).find('#J-showPrice').html(html);
                    }
                }
            });
        },
        //检测演出名称
        checkShowName: function () {
            var $val = $showId.val();

            if($.trim($val).length==0){
                $modaltips.html($msg[0]).parent().show();
                return false;
            }
            return true;
        },
        //检测演出场次
        checkShowTime: function () {
            var $val = $showTimeId.val();

            if($.trim($val).length==0){
                $modaltips.html($msg[1]).parent().show();
                return false;
            }
            return true;
        },
        //检测票面价
        checkShowPrice: function () {
            var $val = $showPriceId.val();

            if($.trim($val).length==0){
                $modaltips.html($msg[2]).parent().show();
                return false;
            }
            return true;
        },
        //检测同行价
        checkPerPrice: function(){
            var $val = $perPrice.val();

            if($.trim($val).length==0){
                $modaltips.html($msg[3]).parent().show();
                return false;
            }else if ($.trim($val)<1){
                $modaltips.html($msg[6]).parent().show();
                return false;
            }
            return true;
        },
        //检测出售数量
        checkSellNum: function(){
            var $val = $sellNum.val();

            if($.trim($val).length==0){
                $modaltips.html($msg[4]).parent().show();
                return false;
            }else if ($.trim($val)<1){
                $modaltips.html($msg[7]).parent().show();
                return false;
            }
            return true;
        },
        //检测有效天数
        checkRestDay: function(){
            var $val = $restDay.val();

            if($.trim($val).length==0){
                $modaltips.html($msg[5]).parent().show();
                return false;
            }else if ($.trim($val)<1){
                $modaltips.html($msg[8]).parent().show();
                return false;
            }
            return true;
        }
    }
})();

//挂单管理
var Order = (function () {
    //列表页
    var $onSell = $('.J-onSell'),   //上架按钮
        $offSell = $('.J-offSell'), //下架按钮
        $tips = $('#J-tips');       //提示

    //详情页
    var $JonSell = $('#J-onSell'),  //上架按钮
        $JoffSell = $('#J-offSell');    //下架按钮

    //修改页
    var $updateForm = $('#J-update-form'),                      //修改挂单表单
        $perPrice = '',       //同行价
        $sellNum = '',       //出售数量
        $restDay = '',       //有效天数
        $updateBtn = $updateForm.find('button[type=submit]'),     //修改按钮
        $msg = {                                                  //提示信息
            0: '同行价不能为空！',
            1: '出售数量不能为空！',
            2: '有效天数不能为空！',
            3: '同行价最小为1元！',
            4: '出售数量最少为1！',
            5: '有效天数至少为1天！'
        };

    return {
        init: function () {
            //事件
            this.bindEvents();
        },
        //事件
        bindEvents: function (){
            var self = this,
                $obj = '',
                $id = '',
                $btnMsg = '',
                $iFlag = 0;

            //列表页上架
            $onSell.click(function () {
                fnModal('温馨提示', "确定要上架该挂单吗？", true, 'modal-sm');

                $obj = $(this);
                $id = $obj.attr('data-id');
                $btnMsg = '上架中';
                $iFlag = 1;
            });
            //列表页下架
            $offSell.click(function () {
                fnModal('温馨提示', "确定要下架该挂单吗？", true, 'modal-sm');

                $obj = $(this);
                $id = $obj.attr('data-id');
                $btnMsg = '下架中';
                $iFlag = 2;
            });

            //详情页上架
            $JonSell.click(function () {
                fnModal('温馨提示', "确定要上架该挂单吗？", true, 'modal-sm');

                $obj = $(this);
                $id = $obj.attr('data-id');
                $btnMsg = '上架中';
                $iFlag = 3;
            });
            //详情页下架
            $JoffSell.click(function () {
                fnModal('温馨提示', "确定要下架该挂单吗？", true, 'modal-sm');

                $obj = $(this);
                $id = $obj.attr('data-id');
                $btnMsg = '下架中';
                $iFlag = 4;
            });

            //确定
            $(doc).on('click', '.J-confirm', function () {
                if (!$iFlag) {
                    $tips.html('非法操作');
                    $tips.parent().show();
                    return;
                }

                var _this = $(this);

                _this.hide();
                _this.after('<button class="btn btn-primary" type="button" disabled>' + $btnMsg + '</button>');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                switch ($iFlag) {
                    case 1: //确定上架
                        $.ajax({
                            type: 'POST',
                            url: PORT.doOnSellOrder,
                            data: {
                                id: $id
                            },
                            dataType: 'json',
                            success: function (data) {
                                if (data.status == 1) {
                                    $obj.parents('tr').find('.J-status').html('已上架');
                                    $obj.remove();
                                }
                                $(doc).find('#J-modal').modal('hide');
                                $tips.html(data.msg).parent().show();
                            }
                        });
                        break;
                    case 2: //确定下架
                        $.ajax({
                            type: 'POST',
                            url: PORT.doOffSellOrder,
                            data: {
                                id: $id
                            },
                            dataType: 'json',
                            success: function (data) {
                                if (data.status == 1) {
                                    $obj.parents('tr').find('.J-status').html('已下架');
                                    $obj.remove();
                                }
                                $(doc).find('#J-modal').modal('hide');
                                $tips.html(data.msg).parent().show();
                            }
                        });
                        break;
                    case 3: //确定上架
                        $.ajax({
                            type: 'POST',
                            url: PORT.doOnSellOrder,
                            data: {
                                id: $id
                            },
                            dataType: 'json',
                            success: function (data) {
                                if (data.status == 1) {
                                    $(doc).find('.J-status').html('已上架');
                                    $obj.after('<button class="btn btn-default btn-lg" type="button" disabled>已上架</button>');
                                    $obj.remove();
                                }
                                $(doc).find('#J-modal').modal('hide');
                                $tips.html(data.msg).parent().show();
                            }
                        });
                        break;
                    case 4: //确定下架
                        $.ajax({
                            type: 'POST',
                            url: PORT.doOffSellOrder,
                            data: {
                                id: $id
                            },
                            dataType: 'json',
                            success: function (data) {
                                if (data.status == 1) {
                                    $(doc).find('.J-status').html('已下架');
                                    $obj.after('<button class="btn btn-default btn-lg" type="button" disabled>已下架</button>');
                                    $obj.remove();
                                }
                                $(doc).find('#J-modal').modal('hide');
                                $tips.html(data.msg).parent().show();
                            }
                        });
                        break;
                }
            });

            //执行修改挂单信息
            $updateBtn.on('click', function(){
                var _this = $(this),
                    $id = _this.attr('data-id');

                    $perPrice = $updateForm.find('input[name=PerPrice]'),   //同行价
                    $sellNum = $updateForm.find('input[name=SellNum]'),     //出售数量
                    $restDay = $updateForm.find('select[name=restDay]');    //有效天数

                var $perPriceVal = $.trim($perPrice.val()),
                    $sellNumVal = $.trim($sellNum.val()),
                    $restDayVal = $.trim($restDay.val());

                if(self.checkPerPrice() && self.checkSellNum() && self.checkRestDay()){
                    _this.hide();
                    _this.after('<button class="btn btn-success btn-lg" type="button" disabled>修改中...</button>');

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'PUT',
                        url: PORT.doUpdateOrder+$id,
                        data: {
                            PerPrice: $perPriceVal,
                            SellNum: $sellNumVal,
                            restDay: $restDayVal
                        },
                        dataType: 'json',
                        success: function (data) {
                            if(data.status == 1){
                                setTimeout(function () {
                                    win.location.href=URL+'order/'+$id;
                                },300)
                            }
                            $tips.html(data.msg).parent().show();
                            _this.show();
                            _this.next().remove();
                        }
                    });
                }
                return false;
            });
        },
        //检测同行价
        checkPerPrice: function(){
            var $val = $perPrice.val();

            if($.trim($val).length==0){
                $tips.html($msg[0]).parent().show();
                return false;
            }else if ($.trim($val)<1){
                $tips.html($msg[3]).parent().show();
                return false;
            }
            return true;
        },
        //检测出售数量
        checkSellNum: function(){
            var $val = $sellNum.val();

            if($.trim($val).length==0){
                $tips.html($msg[1]).parent().show();
                return false;
            }else if ($.trim($val)<1){
                $tips.html($msg[4]).parent().show();
                return false;
            }
            return true;
        },
        //检测有效天数
        checkRestDay: function(){
            var $val = $restDay.val();

            if($.trim($val).length==0){
                $tips.html($msg[2]).parent().show();
                return false;
            }else if ($.trim($val)<1){
                $tips.html($msg[5]).parent().show();
                return false;
            }
            return true;
        }
    }
})();

//管理员管理
var Admin = (function () {
    //修改页
    var $updateInfoForm = $('#J-updateInfo-form'),                      //修改管理员基本信息表单
        $realname = '',       //真实姓名
        $mobile = '',        //手机号
        $email = '',         //邮箱
        $updateInfoBtn = $updateInfoForm.find('button[type=submit]'),     //修改按钮
        $tips = $('#J-tips'),       //提示
        $msg = {                                                  //提示信息
            0: '真实姓名不能为空！',
            1: '手机号不能为空！',
            2: '手机号格式不正确！',
            3: '邮箱不能为空！',
            4: '邮箱格式不正确！',
            5: '原密码不能为空！',
            6: '新密码不能为空！',
            7: '确认新密码不能为空！',
            8: '两次密码不一致！',
            9: '密码为5-18位字符！'
        };
    
    var $updatePwdForm = $('#J-updatePwd-form'),                      //修改管理员密码表单
        $oldpwd = '',       //原密码
        $pwd = '',       //新密码
        $repwd = '',       //确认新密码
        $updatePwdBtn = $updatePwdForm.find('button[type=submit]');     //修改按钮

    //添加管理员
    var $regForm = $('#J-reg-form'),                  //注册表单
        $regBtn = $regForm.find('button[type=submit]'),              //注册按钮
        $name = '',     //账号
        $password = '',       //密码
        $repassword = '',       //确认密码
        $msg2 = {                                         //提示信息
            0: '用户名不能为空！',
            1: '密码不能为空！',
            2: '确认密码不能为空！',
            3: '用户名为5-18位字符！',
            4: '密码为5-18位字符！',
            5: '两次密码输入不一致！',
            6: '注册成功！',
            7: '用户名已经存在，请重新输入！',
            8: '注册失败，账号或密码错误！'
        };

    return {
        init: function () {
            //事件
            this.bindEvents();
        },
        //事件
        bindEvents: function (){
            var self = this;

            //执行修改管理员基本信息
            $updateInfoBtn.on('click', function(){
                var _this = $(this),
                    $id = _this.attr('data-id');

                    $realname = $updateInfoForm.find('input[name=realname]'),
                    $mobile = $updateInfoForm.find('input[name=mobile_number]'),
                    $email = $updateInfoForm.find('input[name=email]');

                var $realnameVal = $.trim($realname.val()),
                    $mobileVal = $.trim($mobile.val()),
                    $emailVal = $.trim($email.val());

                if(self.checkRealname() && self.checkMobile() && self.checkEmail()){
                    _this.hide();
                    _this.after('<button class="btn btn-success btn-lg" type="button" disabled>修改中...</button>');

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'PUT',
                        url: PORT.doUpdateAdmin+$id+'?action=1',
                        data: {
                            realname: $realnameVal,
                            mobile_number: $mobileVal,
                            email: $emailVal
                        },
                        dataType: 'json',
                        success: function (data) {
                            if(data.status == 1){
                                setTimeout(function () {
                                    win.location.href=URL+'admin/'+$id;
                                },300)
                            }
                            $tips.html(data.msg).parent().show();
                            _this.show();
                            _this.next().remove();
                        }
                    });
                }
                return false;
            });

            //执行修改管理员密码
            $updatePwdBtn.on('click', function(){
                var _this = $(this),
                    $id = _this.attr('data-id');

                    $oldpwd = $updatePwdForm.find('input[name=oldpassword]'),
                    $pwd = $updatePwdForm.find('input[name=password]'),
                    $repwd = $updatePwdForm.find('input[name=repassword]');

                var $oldpwdVal = $.trim($oldpwd.val()),
                    $pwdVal = $.trim($pwd.val()),
                    $repwdVal = $.trim($repwd.val());

                if(self.checkOldpwd() && self.checkPwd() && self.checkRepwd()){
                    _this.hide();
                    _this.after('<button class="btn btn-success btn-lg" type="button" disabled>修改中...</button>');

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'PUT',
                        url: PORT.doUpdateAdmin+$id+'?action=2',
                        data: {
                            oldpassword: $oldpwdVal,
                            password: $pwdVal,
                            repassword: $repwdVal
                        },
                        dataType: 'json',
                        success: function (data) {
                            if(data.status == 1){
                                setTimeout(function () {
                                    win.location.href=URL+'admin/'+$id;
                                },300)
                            }
                            $tips.html(data.msg).parent().show();
                            _this.show();
                            _this.next().remove();
                        }
                    });
                }
                return false;
            });

            //执行注册
            $regBtn.on('click', function(){
                var _this = $(this);

                    $name = $regForm.find('input[name=username]'),     //账号
                    $password = $regForm.find('input[name=password]'),       //密码
                    $repassword = $regForm.find('input[name=repassword]');       //确认密码

                var $nameVal = $.trim($name.val()),
                    $passwordVal = $.trim($password.val()),
                    $repasswordVal = $.trim($repassword.val());

                //检测账号 密码
                if(self.checkName() && self.checkPassword() && self.checkRepassword()){
                    _this.hide();
                    _this.after('<button class="btn btn-success btn-lg" type="button" disabled>添加中...</button>');

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'POST',
                        url: PORT.doCreateAdmin,
                        data: {
                            username: $nameVal,
                            password: $passwordVal,
                            repassword: $repasswordVal
                        },
                        dataType: 'json',
                        success: function (data) {
                            if(data.status == 1){
                                setTimeout(function () {
                                    win.location.href=URL+'admin';
                                },300)
                            }
                            $tips.html(data.msg).parent().show();
                            _this.show();
                            _this.next().remove();
                        }
                    });
                }
                return false;
            });
        },
        //检测真实姓名
        checkRealname: function(){
            var $val = $realname.val();

            if($.trim($val).length==0){
                $tips.html($msg[0]).parent().show();
                return false;
            }
            return true;
        },
        //检测手机号
        checkMobile: function(){
            var $val = $mobile.val();
            if($.trim($val).length!=0){
                var re = /^1[34578]\d{9}$/.test($val);
                if(!re){
                    $tips.html($msg[2]).parent().show();
                    return false;
                }
            }else{
                $tips.html($msg[1]).parent().show();
                return false;
            }
            return true;
        },
        //检测邮箱
        checkEmail: function(){
            var $val = $email.val();
            if($.trim($val).length!=0){
                var re =  /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/.test($val);
                if(!re){
                    $tips.html($msg[4]).parent().show();
                    return false;
                }
            }else{
                $tips.html($msg[3]).parent().show();
                return false;
            }
            return true;
        },
        //检测原密码
        checkOldpwd: function(){
            var $val = $oldpwd.val();

            if($.trim($val).length!=0){
                var re = /^[0-9a-zA-z]{5,18}$/.test($val);
                if(!re){
                    $tips.html($msg[9]).parent().show();
                    return false;
                }
            }else{
                $tips.html($msg[5]).parent().show();
                return false;
            }
            return true;
        },
        //检测新密码
        checkPwd: function(){
            var $val = $pwd.val();

            if($.trim($val).length!=0){
                var re = /^[0-9a-zA-z]{5,18}$/.test($val);
                if(!re){
                    $tips.html($msg[9]).parent().show();
                    return false;
                }
            }else{
                $tips.html($msg[6]).parent().show();
                return false;
            }
            return true;
        },
        //检测确认新密码
        checkRepwd: function(){
            var $val = $repwd.val();

            if($.trim($val).length!=0){
                var re = /^[0-9a-zA-z]{5,18}$/.test($val);
                if(!re){
                    $tips.html($msg[9]).parent().show();
                    return false;
                }else if($.trim($val) !== $.trim($pwd.val())){
                    $tips.html($msg[8]).parent().show();
                    return false;
                }
            }else{
                $tips.html($msg[7]).parent().show();
                return false;
            }
            return true;
        },
        //检测用户名
        checkName: function(){
            var $val = $name.val();

            if($.trim($val).length!=0){
                var re = /^[0-9a-zA-z]{5,18}$/.test($val);
                if(!re){
                    $tips.html($msg2[3]).parent().show();
                    return false;
                }
            }else{
                $tips.html($msg2[0]).parent().show();
                return false;
            }
            return true;
        },
        //检测密码
        checkPassword: function(){
            var $val = $password.val();

            if($.trim($val).length!=0){
                var re = /^[0-9a-zA-z]{5,18}$/.test($val);
                if(!re){
                    $tips.html($msg2[4]).parent().show();
                    return false;
                }
            }else{
                $tips.html($msg2[1]).parent().show();
                return false;
            }
            return true;
        },
        //检测确认密码
        checkRepassword: function(){
            var $val = $repassword.val();

            if($.trim($val).length!=0){
                if($password.val() !== $val){
                    $tips.html($msg2[5]).parent().show();
                    return false;
                }
            }else{
                $tips.html($msg2[2]).parent().show();
                return false;
            }
            return true;
        }
    }
})();