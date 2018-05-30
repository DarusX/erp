@extends('layouts.master')
@section('contenido')
<div class="row" id="app">
    <div class="col-sm-12">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">Vacantes</div>
            </div>
            <div class="portlet-body">
                <div class="table-scrollable">
                    <tabla-resultados inline-template>
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Sucursal</th>
                                    <th>Puesto</th>
                                    <th>Actual</th>
                                    <th>Máximo</th>
                                    <th class="text-center">Activo</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="vacante in vacantes" v-if="vacante.cantidad < vacante.maximo">
                                    <td>@{{vacante.sucursal}}</td>
                                    <td>@{{vacante.puesto}}</td>
                                    <td>@{{vacante.cantidad}}</td>
                                    <td>@{{vacante.maximo}}</td>
                                    <td class="text-center">
                                        <i v-if="vacante.id_vacante" class="fa fa-check"></i>
                                    </td>
                                    <td>
                                        <button class="btn green btn-sm" v-if="vacante.id_vacante == null" v-on:click="create(vacante.id_puesto_sucursal)"><i class="fa fa-check"></i></button>
                                        <button class="btn red btn-sm" v-else><i class="fa fa-times" v-on:click="destroy(vacante.id_vacante)"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </tabla-resultados>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('javascript')
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
    window.axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN' : "{{csrf_token()}}"
};
    Vue.component("tabla-resultados", {
        data() {
            return {
                vacantes: [],
            }
        }, created: function(){
                let self = this;
                self.fill()
        }, methods: {
            destroy: function(idVacante){
                let self = this;
                axios.post("/rh/vacantes/eliminar", {
                    id_vacante: idVacante
                }).then(function(){
                    self.fill();
                })
            },
            create: function(idPuestoSucursal){
                let self = this;
                axios.post("/rh/vacantes/crear", {
                    id_puesto_sucursal: idPuestoSucursal
                }).then(function(){
                    self.fill();
                })
            },
            fill: function(){
                axios.get("/rh/vacantes/json").then(response => {
                    this.vacantes = response.data
                })
            }
        }
    })
    const app = new Vue({
        el: "#app"
    });
</script>
@endsection