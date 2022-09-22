<template>
    <div class="left side-menu">
        <div class="sidebar-inner slimscrollleft">
            <!--- Divider -->
            <div id="sidebar-menu">
                <ul>
                    <li class="menu-title">Main</li>

                    <li>
                        <router-link to="/app/dashboard" class="waves-effect waves-primary"><i
                                class="ti-home"></i><span> Dashboard </span></router-link>
                    </li>

                    <li>
                        <router-link to="/app/org" class="waves-effect waves-primary"><i
                                class="ti-home"></i><span> Orgs </span></router-link>
                    </li>

                    <li>
                        <router-link to="/app/user" class="waves-effect waves-primary"><i
                                class="ti-home"></i><span> Users </span></router-link>
                    </li>

                    <li>
                        <a href="#" class="waves-effect waves-primary" @click="logout"><i
                                class="ti-home"></i><span> Logout </span></a>
                    </li>
                </ul>

                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</template>



<script>
    export default {
        data() {
            return {
                
            }
        },
        created() {
            if(!localStorage.getItem("_tk")) window.location.href = process.env.MIX_WEB_URL
        },
        methods: {
            logout() {
                axios.post(`${process.env.MIX_API_URL}/logout`,{}, {
                    headers: {
                        'Authorization': `bearer ${localStorage.getItem('_tk')}`
                    }
                })
                .then((res) => {
                    if(res.data.status) {
                        localStorage.removeItem('_tk')
                        window.location.href = process.env.MIX_WEB_URL
                    }
                })
                .catch((e) => {
                    console.log(e)
                })
            }
        }
    }
</script>