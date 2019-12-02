require('./bootstrap');

import Vue from 'vue';
import PerfectScrollbar from 'vue2-perfect-scrollbar';
import Vuetify, {
    VApp,
    VContent,
    VRow,
    VCol,
    VTabs,
    VTabsSlider,
    VTab,
    VTabItem,
    VContainer,
    VHover,
    VLazy,
    VCard,
    VCardActions,
    VCardText,
    VCardTitle,
    VDataTable,
    VTextField,
    VAppBar,
    VAppBarNavIcon,
    VToolbarTitle,
    VToolbarItems,
    VSpacer,
    VIcon,
    VImg,
    VProgressCircular,
    VDialog,
    VNavigationDrawer,
    VList,
    VListItem,
    VListItemAvatar,
    VListItemTitle,
    VListItemContent,
    VListItemSubtitle,
    VBtn,
    VSheet,
    VDivider
} from 'vuetify/lib';

Vue.use(Vuetify, {
    components: {
        VApp,
        VContent,
        VRow,
        VCol,
        VTabs,
        VTabsSlider,
        VTab,
        VTabItem,
        VContainer,
        VHover,
        VLazy,
        VCard,
        VCardActions,
        VCardText,
        VCardTitle,
        VDataTable,
        VTextField,
        VAppBar,
        VAppBarNavIcon,
        VToolbarTitle,
        VToolbarItems,
        VSpacer,
        VIcon,
        VImg,
        VProgressCircular,
        VDialog,
        VNavigationDrawer,
        VList,
        VListItem,
        VListItemAvatar,
        VListItemTitle,
        VListItemContent,
        VListItemSubtitle,
        VBtn,
        VSheet,
        VDivider
    }
});

Vue.use(PerfectScrollbar);


window.app = new Vue({
    el: '#app',
    vuetify: new Vuetify({}),
    data: {
        holiday: null,
        airMeasurements: null,
        controllers: null,
        
        modal: false,
        modalController: null,
        modaltab: 'tab-properties',
        modalPropertiesHeaders: [
            { value: 'name', text: 'Name' },
            { value: 'Value' },
        ],
        modalSettingsHeaders: [
            { value: 'settingName', text: 'Name' },
            { value: 'settingValue' },
        ],

        QRmodal: false,
        QRcontent: null,

        sidebar: false,
        tab: 'tab-cards',

        search: '',
        tableHeaders: [
            { value: 'id', text: 'ID' },
            { value: 'name', text: 'Name' },
            { value: 'zone', text: 'Zone' },
            { value: 'address', text: 'Address' },
            { value: 'settings.0.settingValue', text: 'Mcu Type' },
            { value: 'settings.1.settingValue', text: 'Header Size' },
            { value: 'settings.2.settingValue', text: 'Reset New Card' },
            { value: 'settings.3.settingValue', text: 'Temperature Difference' },
            { value: 'settings.4.settingValue', text: 'Open Window Alarm' },
            { value: 'settings.5.settingValue', text: 'Auto Arming' },
            { value: 'settings.6.settingValue', text: 'Cards Expired' },
        ]
    },
    created: function () {
        axios.get(window.location.href + '/api/holiday-and-air').then(function (r) {
            app.holiday = r.data.holiday;
            app.airMeasurements = r.data.air;
        });

        var proxyurl = 'https://cors-anywhere.herokuapp.com/';
        axios.get(proxyurl + 'http://ae.hr/rooms_status.php').then(function (r) {
            app.controllers = r.data.controllers;
        });
    },
    methods: {
        showController: function (id) {
            var id = typeof id == 'object' ? id.id : id;

            for (var i = 0; i < this.controllers.length; i++) {
                if (id == this.controllers[i].id) {
                    var c = this.controllers[i];
                    var properties = [];

                    c.properties.forEach(function(p, i) {
                        if (p.id == 11) {
                            p.name = 'Set temperature';
                            p.Value += ' °C';
                            properties.push(p);
                        } else if (p.id == 12) {
                            p.name = 'Current temperature';
                            p.Value += ' °C';
                            properties.push(p);
                        } else if (p.id == 27 && p.Value == 1) {
                            p.Value = null;
                            properties.push(p);
                        } else if (p.id == 48 && p.Value == 1) {
                            p.name = 'Occupied';
                            p.Value = null;
                            properties.push(p);
                        }
                    });

                    c.properties = properties;

                    app.modalController = c;
                    app.modal = true;

                    break;
                }
            }
        }
    }
});