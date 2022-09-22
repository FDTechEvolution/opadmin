<template>
    <div>
        <div id="wrapper">
            <topbar-component></topbar-component>
            <sidebar-component></sidebar-component>
        </div>
        <div class="content-page">
            <!-- Start content -->
            <div class="content">
                <div class="container-fluid">
                    <router-view></router-view>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    
}
</script>