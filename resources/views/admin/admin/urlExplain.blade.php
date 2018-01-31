@extends('admin.master.base')

@section('content')
    <div class="page-header row">
        <h1>前台URL说明</h1>
    </div>

    <div class="wrap">
        <table class="table table-striped table-bordered ">
            <tbody>
            <tr>
                <td>
                    <h4>微信授权登录</h4>
                    https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxf291235684db6dc5&redirect_uri=http://www.piaobuyer.com/bind.html?return_url=http%3A%2F%2Fwww.piaobuyer.com%2F&response_type=code&scope=snsapi_userinfo&state=123&uin=NjQ3OTA0MDAw&key=65f928c75b96cd0283fa7a8c415fa9124594db454a0c61b67f41b04935232ecf711811652807d4133ce18295061badbd&pass_ticket=3VQ+XdJPL56vSzW1zKL3YLm65tiiNEzH4N5ocRv4vE1+CG6YTt+R7p4gHNAhQN+cBbBe7E4CZCpHkvDTyKeorw==
                </td>
            </tr>
            <tr>
                <td>
                    <h4>绑定手机</h4>
                    <p><b>默认</b>  http://www.piaobuyer.com/bind.html</p>
                    <p><b>return_url 表示来源页url</b>  http://www.piaobuyer.com/bind.html?return_url=http://www.piaobuyer.com/&code=011FMYA82azfpO0TmcB82HM2B82FMYAe&state=123</p>
                </td>
            </tr>
            <tr>
                <td>
                    <h4>首页</h4>
                    <p><b>默认</b>  http://www.piaobuyer.com/</p>
                    <p><b>code 表示微信需要的参数</b>  http://www.piaobuyer.com/?code=041MaZRI1S8cr70uAHTI1JjdSI1MaZRp&state=123</p>
                </td>
            </tr>
            <tr>
                <td>
                    <h4>搜索 / 卖票</h4>
                    http://www.piaobuyer.com/search.html
                </td>
            </tr>
            <tr>
                <td>
                    <h4>搜索结果</h4>
                    <b>k 表示搜索词</b>  http://www.piaobuyer.com/search-result.html?k=%E5%91%A8%E6%9D%B0%E4%BC%A6
                </td>
            </tr>
            <tr>
                <td>
                    <h4>演出挂单</h4>
                    <b>sid 表示演出id</b>  http://www.piaobuyer.com/show.html?sid=359
                </td>
            </tr>
            <tr>
                <td>
                    <h4>发布挂单</h4>
                    <b>sid 表示演出id</b>  http://www.piaobuyer.com/supply.html?sid=359
                </td>
            </tr>
            <tr>
                <td>
                    <h4>我的</h4>
                    http://www.piaobuyer.com/my.html
                </td>
            </tr>
            <tr>
                <td>
                    <h4>我的挂单</h4>
                    http://www.piaobuyer.com/myshow.html
                </td>
            </tr>
            <tr>
                <td>
                    <h4>挂单明细</h4>
                    <b>sid 表示演出id；type 表示1 在售/2 下架</b>  http://www.piaobuyer.com/myorder.html?sid=664&type=2
                </td>
            </tr>
            <tr>
                <td>
                    <h4>意见反馈</h4>
                    http://www.piaobuyer.com/feedback.html
                </td>
            </tr>
            <tr>
                <td>
                    <h4>提交演出信息</h4>
                    http://www.piaobuyer.com/addshow.html
                </td>
            </tr>
            </tbody>
        </table>
    </div>
@endsection