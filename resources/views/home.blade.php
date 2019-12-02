<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">

 <title>Adria</title>

 <!-- Styles -->
 <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
  <div id="app">
    <v-app>
      <!-- HEADER -->
      <v-app-bar height="75" clipped-left app color="grey darken-4" absolute dark>
        <v-toolbar-title>
          <a href="{{ url('/') }}">
            <img width="130" class="mt-1" src="img/logo.png">
          </a>
        </v-toolbar-title>

        <v-spacer></v-spacer>

        <template v-if="$vuetify.breakpoint.smAndUp">
          <template v-if="airMeasurements">
            <v-toolbar-title class="mr-5 pr-2 subtitle-2 grey--text text--lighten-1">Rijeka Air Quality</v-toolbar-title>
            <div class="mr-3 text-center" v-for="(measurement, i) in airMeasurements" :key="i">
              <div class="lh-1 mb-sm-2 mb-md-0 font-weight-black caption text-uppercase">@{{ measurement.parameter }}</div>
              <div class="lh-1 caption font-weight-thin d-md-inline">@{{ measurement.value }}</div>
              <div class="lh-1 caption font-weight-thin d-md-inline">@{{ measurement.unit }}</div>
            </div>
          </template>
        </template>
        <template v-else>
          <v-app-bar-nav-icon @click="sidebar = !sidebar">
            <v-icon height="48" color="#fff">mdi-menu</v-icon>
          </v-app-bar-nav-icon>
        </template>
      </v-app-bar>

      <!-- SIDEBAR -->
      <v-navigation-drawer clipped app v-model="sidebar" :temporary="$vuetify.breakpoint.xs" :permanent="$vuetify.breakpoint.smAndUp">
        <perfect-scrollbar>
          <v-list dense class="pt-0">
            <v-toolbar-title class="pa-4 mb-2 grey lighten-4">Controllers</v-toolbar-title>
            <v-list-item v-for="(controller, i) in controllers" :key="i" @click="showController(controller.id)">
              <v-list-item-content>
                <v-list-item-title>
                  <v-btn fab small elevation="0" class="float-left mr-4" color="default">
                    @{{ controller.zone }}
                  </v-btn>

                  <small class="caption float-left">
                    <div>Name: @{{ controller.name }}</div>
                    <div>Address: @{{ controller.address }}</div>
                  </small>
                </v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-list>

          <template v-if="$vuetify.breakpoint.xs && airMeasurements">
            <v-toolbar-title class="pa-4 grey lighten-4">Rijeka Air Quality</v-toolbar-title>

            <v-list-item v-for="(measurement, i) in airMeasurements" :key="i">
              <v-list-item-content>
                <v-list-item-title>
                  <v-btn fab small elevation="0" class="float-left mr-4" color="default">
                    @{{ measurement.parameter }}
                  </v-btn>

                  <small class="caption float-left" style="line-height: 40px">@{{ measurement.value + ' ' + measurement.unit }}</small>
                </v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </template>

          <template v-if="holiday">
            <v-toolbar-title class="pa-4 grey lighten-4">Upcoming holiday</v-toolbar-title>

            <v-list-item>
              <v-list-item-content>
                <v-list-item-title>
                  <v-btn fab small elevation="0" class="float-left mr-4" color="default">
                    <v-icon>mdi-calendar-month</v-icon>
                  </v-btn>

                  <small class="caption float-left">
                    <div>@{{ holiday.name }}</div>
                    @{{ holiday.date }}
                  </small>
                </v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </template>
        </perfect-scrollbar>
      </v-navigation-drawer>

      <!-- CONTENT -->
      <v-content>
        <div class="pa-4">

          <v-tabs v-model="tab" background-color="grey lighten-4" color="grey darken-4">
            <v-tabs-slider></v-tabs-slider>

            <v-tab href="#tab-cards">
              <v-icon>mdi-apps</v-icon>
            </v-tab>

            <v-tab href="#tab-table">
              <v-icon>mdi-table-large</v-icon>
            </v-tab>

            <v-tab-item value="tab-cards">
              <v-container fluid>
                <v-row dense>
                  <v-col v-for="(controller, i) in controllers" :key="i" sm="6" md="4" lg="3">
                    <v-card outlined>
                      <v-list-item three-line>
                        <v-list-item-content>
                          <div class="overline mb-4">Address: @{{ controller.address }}</div>
                          <v-list-item-title class="headline mb-1">Zone: @{{ controller.zone }}</v-list-item-title>
                          <v-list-item-subtitle>Name: @{{ controller.name }}</v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>

                      <v-card-actions>
                        <v-btn color="default" elevation="0" @click="showController(controller.id)">
                          SHOW MORE
                        </v-btn>

                        <v-spacer></v-spacer>

                        <v-btn color="default" fab small elevation="0" @click="QRmodal = true; QRcontent = controller.name">
                          <v-icon>mdi-qrcode</v-icon>
                        </v-btn>
                      </v-card-actions>
                    </v-card>
                  </v-col>
                </v-row>
              </v-container>
            </v-tab-item>

            <v-tab-item value="tab-table">
              <v-text-field v-model="search" label="Search controllers" single-line full-width color="grey darken-4"></v-text-field>

              <v-data-table 
                @click:row="showController"
                :headers="tableHeaders"
                :items="controllers"
                :search="search"
                :items-per-page="5"
              ></v-data-table>
            </v-tab-item>
          </v-tabs>
        </div>
      </v-content>

      <!-- Controller dialog -->
      <v-dialog v-model="modal" width="600px">
        <v-card v-if="modalController">
          <v-btn fab small elevation="0" class="float-right ma-3 ml-2" @click="modal = false">
            <v-icon>mdi-close</v-icon>
          </v-btn>

          <v-btn fab small elevation="0" class="float-right mt-3" @click="QRmodal = true; QRcontent = controller.name">
            <v-icon>mdi-qrcode</v-icon>
          </v-btn>

          <v-card-title class="px-4">
            <span class="headline">@{{ modalController.name }}</span>
          </v-card-title>

          <div class="px-4 caption">Zone: @{{ modalController.zone }}</div>
          <div class="px-4 caption pb-3">Address: @{{ modalController.address }}</div>

          <v-tabs v-model="modaltab" background-color="grey lighten-4" color="grey darken-4">
            <v-tabs-slider></v-tabs-slider>

            <v-tab href="#tab-properties">
              Properties
            </v-tab>

            <v-tab href="#tab-settings">
              Settings
            </v-tab>

            <v-tab-item value="tab-properties">
              <v-data-table 
                :headers="modalPropertiesHeaders"
                :items="modalController.properties"
                hide-default-footer
                hide-default-header
              ></v-data-table>
            </v-tab-item>

            <v-tab-item value="tab-settings">
              <v-data-table 
                :headers="modalSettingsHeaders"
                :items="modalController.settings"
                hide-default-footer
                hide-default-header
              ></v-data-table>
            </v-tab-item>
          </v-tabs>
        </v-card>
      </v-dialog>

      <!-- QR code dialog -->
      <v-dialog v-model="QRmodal" width="182px">
        <v-card class="pa-4">
          <v-img width="150" height="150" :src="'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' + QRcontent">
            <template v-slot:placeholder>
              <v-row class="fill-height ma-0" align="center" justify="center">
                <v-progress-circular indeterminate color="grey darken-4"></v-progress-circular>
              </v-row>
            </template>
          </v-img>
        </v-card>
      </v-dialog>
    </v-app>
  </div>
  
  <!-- Scripts -->
  <script src="{{ asset(mix('js/app.js')) }}"></script>
</body>
</html>