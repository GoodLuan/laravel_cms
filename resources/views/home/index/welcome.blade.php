<tagLib name='cx,html' />
<style type="text/css">
.index_tab th, .index_tab td {border:solid 1px #e8e8e8;}
.index_tab th {background:#eee; padding:10px;}
</style>
<div class="index_tab">
    <?php if( in_array($_SESSION['groupInfo']['label'], explode(',',wstConfig('wst_welcome_auth_group'))) ) { ?>
    <div style="text-align: center;width: 100%;font-size: 20px;color:#555;margin: 20px auto 30px;"><span>最新数据播报</span></div>
    <table class="t_data_list_non" width="100%" style="height:200px;">
		<tr class="mess_tr_h">
            <th>线上商品SKU数</th><th>本月总销售金额</th><th style=" border-bottom:0px;">本月订单详情</th>
        </tr>
        <tr>
            <td rowspan="2" style="border-right:0;">{$sku_num}</td>
            <td style="border-right:0; height:70px;">￥{$order_stat['month_all_amount']|number_format=2}</td>
            <td rowspan="2" style="border:0; padding:0;">
                <table width="100%">
                    <tr>
                        <th>总订单</th>
                        <th>完成订单</th>
                        <th>完成率</th>
                        <th>退货率</th>
                    </tr>
                    <tr height="120px">
                        <td>{$order_stat.all_num}</td>
                        <td>{$order_stat.finish_num}</td>
                        <td>{$order_stat.month_finish_rate}%</td>
                        <td>{$order_stat.month_return_rate}%</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
        	<td style="padding:0; border-right:none;">
            	<table width="100%">
                    <tr>
                        <th style="border:none; border-right:solid 1px #e8e8e8;">PC端本月销售金额</th>
                        <th style="border:none;">移动端本月销售金额</th>
                        <th style="border:none;">CPS本月销售金额</th>
                    </tr>
                    <tr height="51px">
                        <td style="border:none; border-right:solid 1px #e8e8e8;">￥{$order_stat['month_pc_amount']|number_format=2}</td>
                        <td style="border:none;">￥{$order_stat['month_mobile_amount']|number_format=2}</td>
                        <td style="border:none;">￥{$order_stat['month_cps_amount']}</td>
                    </tr>
                </table>
            </td>
        </tr>
	</table>
    <?php } ?>
    <div style="color: #555;">
        <div style="width: 780px;border-bottom: 1px solid #555;margin: 0 auto;">
            <div style="margin: 40px auto 20px;width: 100%;text-align: center;">
                <p style="font-size: 20px;">我们是一个创业团队，我们是一群追求完美的疯子。</p>
            </div>
        </div>
        <div style="text-align: center;width: 100%;margin: 10px auto;font-size: 12px;">
            <p>Copyright © 2008-2017 美西时尚 | MEICI, 上海美昔贸易有限公司. All right Reserved</p>
        </div>
    </div>
</div>