(function (factory) {
    if (typeof define === 'function' && define.amd) { define('ChineseDistricts', [], factory); } else { factory(); }
})(function () {
    if (typeof window !== 'undefined') { window.ChineseDistricts = ChineseDistricts; }
    return ChineseDistricts;
});