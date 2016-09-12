/**
 * Created by zhu yongxuan on 2016/8/1.
 */

function computeWholeValue(singleValue) {
    var count = $("#dinnernumber").val();
    $("#dinnerWholeValue").html(count * singleValue + "元");
}
