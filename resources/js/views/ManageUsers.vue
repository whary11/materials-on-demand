<template>
    <div class="card">
        <div class="card-body" ref="tableUsersManage">
            <button class="btn btn-primary"><i class="cis-user-female-plus"></i> Crear usuario</button>
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
                        <div class="dropdown-menu dropdown-menu-end options-dmenu" style="position:absolute;inset: auto auto 0px 0px; margin: 0px; transform: translate3d(-142px, -28px, 0px);" v-if="can(['add_headquarters_to_user'])">
                            <a v-if="can(['add_headquarters_to_user'])" class="dropdown-item text-dark" href="#" @click.prevent="addHeadquarter(row)">Agregar sede</a>
                        </div>
                    </div>
                </template>
                <template v-slot:child_row="{row}">
                    <SubRow :row="row" @addPermissions="addHeadquarterEvent" @addRoles="addHeadquarterEvent"/>
                </template>
            </v-server-table>
        </div>
        <template v-if="can(['add_headquarters_to_user'])">
            <AddHeadquarter :userdrdr="userEdit" @addHeadquarter="addHeadquarterEvent"/>
        </template>
    </div>
</template>

<script>
import { getUsersManage } from '../utils/services/user';
import AddHeadquarter from '../components/ManageUsers/AddHeadquarter';
import SubRow from '../components/ManageUsers/SubRow';

export default {
    components:{
        AddHeadquarter,
        SubRow
    },
    data() {
        return {
            columns: ['id','avatar','fullname','email','headquarters','options'],
            headings: {
                headquarters: "Sedes",
                options: ''
            },
            userEdit:{}
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
            this.$modal.show('add_headquarter')
            console.log("------------addHeadquarter-----------------");
            console.log(user);
            console.log("------------addHeadquarter-----------------");
        },
        addHeadquarterEvent(){
            this.$refs.usersManageTable.refresh()
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