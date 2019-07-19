<template>
    <div class="home-page">
        <div class="container d-flex">
            <transition name="page" mode="out-in">
                <router-view></router-view>
            </transition>
        </div>
    </div>
</template>