define("utilities", ["require", "exports"], function (require, exports) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    exports.get_site_root = get_site_root;
    function get_site_root() {
        const protocol = window.location.protocol;
        const host = window.location.host;
        return (host === "localhost")
            ? `${protocol}//localhost/travail/stardew_dashboard/`
            : `${protocol}//stardew-dashboard.42web.io/`;
    }
});
define("app", ["require", "exports", "utilities"], function (require, exports, utilities_1) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    const root = (0, utilities_1.get_site_root)();
    console.log(root);
});
