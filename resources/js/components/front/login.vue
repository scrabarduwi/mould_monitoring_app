<template>
    <div class="login-form" style="margin: auto;">
        <form>
            <h2>Mould Monitoring</h2>
            <hr>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" v-model="loginDetails.email" class="form-control">
                <span class="help-block" v-if="errorsEmail">
                    {{emailError}}
                </span>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" v-model="loginDetails.password" class="form-control">
                <span class="help-block" v-if="errorsPassword">
                    {{passwordError}}
                </span>
            </div>
            <div class="form-group">
                <input type="checkbox" name="remember" id="remember" v-model="loginDetails.remember"> Remember Me
            </div>
            <div class="form-group">
                <button type="submit" @click.prevent="loginPost" class="btn btn-primary">LOGIN</button>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            loginDetails: {
                email: '',
                password: '',
                remember: false
            },
            errorsEmail: false,
            errorsPassword: false,
            emailError: null,
            passwordError: null
        }
    },
    methods: {
        loginPost(){
            let _self = this;
            axios.post('/login', _self.loginDetails)
            .then((response) => {
                location.href = "/dashboard";
            })
            .catch((error) => {
                var errors = error.response;
                console.log(errors);

                _self.errorsEmail = false;
                _self.errorsPassword = false;

                if(errors.statusText === "Unprocessable Entity" || errors.status === 422){
                    if(errors.data.errors) {
                        if(errors.data.errors.email) {
                            _self.errorsEmail = true;
                            _self.emailError = _.isArray(errors.data.errors.email) ? errors.data.errors.email[0]: errors.data.errors.email;
                        }
                        if(errors.data.errors.password) {
                            _self.errorsPassword = true;
                            _self.passwordError = _.isArray(errors.data.errors.password) ? errors.data.errors.password[0]: errors.data.errors.password;
                        }
                    }
                }
            });
        }
    }
}
</script>
