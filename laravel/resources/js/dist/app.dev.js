"use strict";

var _vue = _interopRequireDefault(require("vue"));

var _inertiaVue = require("@inertiajs/inertia-vue");

var _laravelJetstream = require("laravel-jetstream");

var _portalVue = _interopRequireDefault(require("portal-vue"));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { "default": obj }; }

require('./bootstrap');

require('moment');

_vue["default"].mixin({
  methods: {
    route: route
  }
});

_vue["default"].use(_inertiaVue.InertiaApp);

_vue["default"].use(_laravelJetstream.InertiaForm);

_vue["default"].use(_portalVue["default"]);

_vue["default"].prototype.$echo = window.Echo;
var app = document.getElementById('app');
new _vue["default"]({
  render: function render(h) {
    return h(_inertiaVue.InertiaApp, {
      props: {
        initialPage: JSON.parse(app.dataset.page),
        resolveComponent: function resolveComponent(name) {
          return require("./Pages/".concat(name))["default"];
        }
      }
    });
  }
}).$mount(app);