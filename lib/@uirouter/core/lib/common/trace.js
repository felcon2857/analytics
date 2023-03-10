"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.trace = exports.Trace = exports.Category = void 0;
/**
 * # Transition tracing (debug)
 *
 * Enable transition tracing to print transition information to the console,
 * in order to help debug your application.
 * Tracing logs detailed information about each Transition to your console.
 *
 * To enable tracing, import the [[Trace]] singleton and enable one or more categories.
 *
 * ### ES6
 * ```js
 * import {trace} from "@uirouter/core";
 * trace.enable(1, 5); // TRANSITION and VIEWCONFIG
 * ```
 *
 * ### CJS
 * ```js
 * let trace = require("@uirouter/core").trace;
 * trace.enable("TRANSITION", "VIEWCONFIG");
 * ```
 *
 * ### Globals
 * ```js
 * let trace = window["@uirouter/core"].trace;
 * trace.enable(); // Trace everything (very verbose)
 * ```
 *
 * ### Angular 1:
 * ```js
 * app.run($trace => $trace.enable());
 * ```
 *
 * @packageDocumentation
 */
var hof_1 = require("../common/hof");
var predicates_1 = require("../common/predicates");
var strings_1 = require("./strings");
var safeConsole_1 = require("./safeConsole");
function uiViewString(uiview) {
    if (!uiview)
        return 'ui-view (defunct)';
    var state = uiview.creationContext ? uiview.creationContext.name || '(root)' : '(none)';
    return "[ui-view#" + uiview.id + " " + uiview.$type + ":" + uiview.fqn + " (" + uiview.name + "@" + state + ")]";
}
var viewConfigString = function (viewConfig) {
    var view = viewConfig.viewDecl;
    var state = view.$context.name || '(root)';
    return "[View#" + viewConfig.$id + " from '" + state + "' state]: target ui-view: '" + view.$uiViewName + "@" + view.$uiViewContextAnchor + "'";
};
function normalizedCat(input) {
    return predicates_1.isNumber(input) ? Category[input] : Category[Category[input]];
}
/**
 * Trace categories Enum
 *
 * Enable or disable a category using [[Trace.enable]] or [[Trace.disable]]
 *
 * `trace.enable(Category.TRANSITION)`
 *
 * These can also be provided using a matching string, or position ordinal
 *
 * `trace.enable("TRANSITION")`
 *
 * `trace.enable(1)`
 */
var Category;
(function (Category) {
    Category[Category["RESOLVE"] = 0] = "RESOLVE";
    Category[Category["TRANSITION"] = 1] = "TRANSITION";
    Category[Category["HOOK"] = 2] = "HOOK";
    Category[Category["UIVIEW"] = 3] = "UIVIEW";
    Category[Category["VIEWCONFIG"] = 4] = "VIEWCONFIG";
})(Category || (Category = {}));
exports.Category = Category;
var _tid = hof_1.parse('$id');
var _rid = hof_1.parse('router.$id');
var transLbl = function (trans) { return "Transition #" + _tid(trans) + "-" + _rid(trans); };
/**
 * Prints UI-Router Transition trace information to the console.
 */
var Trace = /** @class */ (function () {
    /** @internal */
    function Trace() {
        /** @internal */
        this._enabled = {};
        this.approximateDigests = 0;
    }
    /** @internal */
    Trace.prototype._set = function (enabled, categories) {
        var _this = this;
        if (!categories.length) {
            categories = Object.keys(Category)
                .map(function (k) { return parseInt(k, 10); })
                .filter(function (k) { return !isNaN(k); })
                .map(function (key) { return Category[key]; });
        }
        categories.map(normalizedCat).forEach(function (category) { return (_this._enabled[category] = enabled); });
    };
    Trace.prototype.enable = function () {
        var categories = [];
        for (var _i = 0; _i < arguments.length; _i++) {
            categories[_i] = arguments[_i];
        }
        this._set(true, categories);
    };
    Trace.prototype.disable = function () {
        var categories = [];
        for (var _i = 0; _i < arguments.length; _i++) {
            categories[_i] = arguments[_i];
        }
        this._set(false, categories);
    };
    /**
     * Retrieves the enabled stateus of a [[Category]]
     *
     * ```js
     * trace.enabled("VIEWCONFIG"); // true or false
     * ```
     *
     * @returns boolean true if the category is enabled
     */
    Trace.prototype.enabled = function (category) {
        return !!this._enabled[normalizedCat(category)];
    };
    /** @internal called by ui-router code */
    Trace.prototype.traceTransitionStart = function (trans) {
        if (!this.enabled(Category.TRANSITION))
            return;
        safeConsole_1.safeConsole.log(transLbl(trans) + ": Started  -> " + strings_1.stringify(trans));
    };
    /** @internal called by ui-router code */
    Trace.prototype.traceTransitionIgnored = function (trans) {
        if (!this.enabled(Category.TRANSITION))
            return;
        safeConsole_1.safeConsole.log(transLbl(trans) + ": Ignored  <> " + strings_1.stringify(trans));
    };
    /** @internal called by ui-router code */
    Trace.prototype.traceHookInvocation = function (step, trans, options) {
        if (!this.enabled(Category.HOOK))
            return;
        var event = hof_1.parse('traceData.hookType')(options) || 'internal', context = hof_1.parse('traceData.context.state.name')(options) || hof_1.parse('traceData.context')(options) || 'unknown', name = strings_1.functionToString(step.registeredHook.callback);
        safeConsole_1.safeConsole.log(transLbl(trans) + ":   Hook -> " + event + " context: " + context + ", " + strings_1.maxLength(200, name));
    };
    /** @internal called by ui-router code */
    Trace.prototype.traceHookResult = function (hookResult, trans, transitionOptions) {
        if (!this.enabled(Category.HOOK))
            return;
        safeConsole_1.safeConsole.log(transLbl(trans) + ":   <- Hook returned: " + strings_1.maxLength(200, strings_1.stringify(hookResult)));
    };
    /** @internal called by ui-router code */
    Trace.prototype.traceResolvePath = function (path, when, trans) {
        if (!this.enabled(Category.RESOLVE))
            return;
        safeConsole_1.safeConsole.log(transLbl(trans) + ":         Resolving " + path + " (" + when + ")");
    };
    /** @internal called by ui-router code */
    Trace.prototype.traceResolvableResolved = function (resolvable, trans) {
        if (!this.enabled(Category.RESOLVE))
            return;
        safeConsole_1.safeConsole.log(transLbl(trans) + ":               <- Resolved  " + resolvable + " to: " + strings_1.maxLength(200, strings_1.stringify(resolvable.data)));
    };
    /** @internal called by ui-router code */
    Trace.prototype.traceError = function (reason, trans) {
        if (!this.enabled(Category.TRANSITION))
            return;
        safeConsole_1.safeConsole.log(transLbl(trans) + ": <- Rejected " + strings_1.stringify(trans) + ", reason: " + reason);
    };
    /** @internal called by ui-router code */
    Trace.prototype.traceSuccess = function (finalState, trans) {
        if (!this.enabled(Category.TRANSITION))
            return;
        safeConsole_1.safeConsole.log(transLbl(trans) + ": <- Success  " + strings_1.stringify(trans) + ", final state: " + finalState.name);
    };
    /** @internal called by ui-router code */
    Trace.prototype.traceUIViewEvent = function (event, viewData, extra) {
        if (extra === void 0) { extra = ''; }
        if (!this.enabled(Category.UIVIEW))
            return;
        safeConsole_1.safeConsole.log("ui-view: " + strings_1.padString(30, event) + " " + uiViewString(viewData) + extra);
    };
    /** @internal called by ui-router code */
    Trace.prototype.traceUIViewConfigUpdated = function (viewData, context) {
        if (!this.enabled(Category.UIVIEW))
            return;
        this.traceUIViewEvent('Updating', viewData, " with ViewConfig from context='" + context + "'");
    };
    /** @internal called by ui-router code */
    Trace.prototype.traceUIViewFill = function (viewData, html) {
        if (!this.enabled(Category.UIVIEW))
            return;
        this.traceUIViewEvent('Fill', viewData, " with: " + strings_1.maxLength(200, html));
    };
    /** @internal called by ui-router code */
    Trace.prototype.traceViewSync = function (pairs) {
        if (!this.enabled(Category.VIEWCONFIG))
            return;
        var uivheader = 'uiview component fqn';
        var cfgheader = 'view config state (view name)';
        var mapping = pairs
            .map(function (_a) {
            var _b;
            var uiView = _a.uiView, viewConfig = _a.viewConfig;
            var uiv = uiView && uiView.fqn;
            var cfg = viewConfig && viewConfig.viewDecl.$context.name + ": (" + viewConfig.viewDecl.$name + ")";
            return _b = {}, _b[uivheader] = uiv, _b[cfgheader] = cfg, _b;
        })
            .sort(function (a, b) { return (a[uivheader] || '').localeCompare(b[uivheader] || ''); });
        safeConsole_1.safeConsole.table(mapping);
    };
    /** @internal called by ui-router code */
    Trace.prototype.traceViewServiceEvent = function (event, viewConfig) {
        if (!this.enabled(Category.VIEWCONFIG))
            return;
        safeConsole_1.safeConsole.log("VIEWCONFIG: " + event + " " + viewConfigString(viewConfig));
    };
    /** @internal called by ui-router code */
    Trace.prototype.traceViewServiceUIViewEvent = function (event, viewData) {
        if (!this.enabled(Category.VIEWCONFIG))
            return;
        safeConsole_1.safeConsole.log("VIEWCONFIG: " + event + " " + uiViewString(viewData));
    };
    return Trace;
}());
exports.Trace = Trace;
/**
 * The [[Trace]] singleton
 *
 * #### Example:
 * ```js
 * import {trace} from "@uirouter/core";
 * trace.enable(1, 5);
 * ```
 */
var trace = new Trace();
exports.trace = trace;
//# sourceMappingURL=trace.js.map