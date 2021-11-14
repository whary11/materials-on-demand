<template>
    <div class="card">
        <div class="card-body" ref="tableUsersManage">
            <button class="btn btn-sm btn-primary" @click.prevent="show(modal_user)"><i class="cis-user-female-plus"></i> Crear usuario</button>
            <v-server-table :columns="columns" :options="{requestFunction,headings,childRowTogglerFirst:false}" ref="usersManageTable">
                <template slot="avatar" slot-scope="{row}">
                    <div>
                        <div class="avatar avatar-md shadow">
                            <img class="avatar-img" :src="row.avatar" :alt="row.email">
                            <span class="avatar-status bg-success"></span>
                        </div>
                    </div>
                </template>
                <template slot="headquarters" slot-scope="{row}">
                    <div>
                        <span class="rounded-pill badge bg-primary m-1" v-for="(headquarter) in row.headquarters" :key="headquarter.id">
                            {{ headquarter.name }}
                        </span>
                    </div>
                </template>

                <template slot="options" slot-scope="{row}">
                    <div class="dropdown">
                        <button class="btn btn-transparent p-0 dark:text-high-emphasis" type="button" data-coreui-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <svg class="icon">
                                <use xlink:href="/dist/vendors/@coreui/icons/svg/free.svg#cil-options"></use>
                            </svg>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end options-dmenu" style="position:absolute;inset: auto auto 0px 0px; margin: 0px; transform: translate3d(-142px, -28px, 0px);" v-if="can(['add_headquarters_to_user'], 'Agregar sedes a un usuario.')">
                            <a  class="dropdown-item text-dark" href="#" @click.prevent="addHeadquarter(row)">Agregar sede</a>
                        </div>
                    </div>
                </template>
                <template v-slot:child_row="{row}">
                    <SubRow :row="row" @addPermissions="addHeadquarterEvent" @addRoles="addHeadquarterEvent"/>
                </template>
            </v-server-table>
        </div>
        <template v-if="can(['add_headquarters_to_user'], 'Agregar sedes a un usuario.')">
            <AddHeadquarter :userdrdr="userEdit" @addHeadquarter="addHeadquarterEvent"/>
        </template>

        <template v-if="can(['create_new_user_backoffice'], 'Crear usuarios backoffice')">
            <AddNewUser :name="modal_user"/>
        </template>
    </div>
</template>

<script>
import { getUsersManage } from '../utils/services/user';
import AddHeadquarter from '../components/ManageUsers/AddHeadquarter';
import SubRow from '../components/ManageUsers/SubRow';
import AddNewUser from '../components/ManageUsers/AddNewUser';

export default {
    components:{
        AddHeadquarter,
        SubRow,
        AddNewUser
    },
    data() {
        return {
            columns: ['id','avatar','fullname','email','headquarters','options'],
            headings: {
                headquarters: "Sedes",
                options: ''
            },
            userEdit:{},
            modal_user:"modal_user"
        }
    },
    mounted() {
       
    },
    methods: {
        requestFunction(data) {
            return getUsersManage(data, this)
        },
        addHeadquarter(user){
            this.userEdit = user
            this.show('add_headquarter')

        },
        addHeadquarterEvent(){
            this.$refs.usersManageTable.refresh()
        },
        show(name){
           this.$modal.show(name) 
        }
    }



}
</script>

<style>
.VueTables__child-row-toggler {
    width: 16px;
    height: 16px;
    line-height: 16px;
    display: block;
    margin: auto;
    text-align: center;
}

.VueTables__child-row-toggler--closed::before {
    content: "+";
    color:var(--cui-success);
}

.VueTables__child-row-toggler--open::before {
    content: "-";
    color: var(--cui-danger);
}

.options-dmenu{
    position: fixed !important;
    top:30%; 
}

    
</style>