<template>
    <div class="page-wrapper">
        <div class="authentication-box">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-7">
                        <div class="card tab2-card">
                            <div class="card-body">
                                <ul
                                    class="nav nav-tabs nav-material"
                                    id="top-tab"
                                    role="tablist"
                                >
                                    <li class="nav-item">
                                        <a
                                            class="nav-link active"
                                            id="top-profile-tab"
                                            data-bs-toggle="tab"
                                            href="#top-profile"
                                            role="tab"
                                            aria-controls="top-profile"
                                            aria-selected="true"
                                            ><span class="icon-user me-2"></span
                                            >Login</a
                                        >
                                    </li>
                                    
                                </ul>
                                <div class="tab-content" id="top-tabContent">
                                    <div
                                        class="tab-pane fade show active"
                                        id="top-profile"
                                        role="tabpanel"
                                        aria-labelledby="top-profile-tab"
                                    >
                                        <form class="form-horizontal auth-form">
                                            <div class="form-group">
                                                <input
                                                    required=""
                                                    name="email"
                                                    type="email"
                                                    class="form-control"
                                                    placeholder="Email"
                                                    id="email"
                                                />
                                            </div>
                                            <div class="form-group">
                                                <input
                                                    required=""
                                                    name="password"
                                                    type="password"
                                                    class="form-control"
                                                    placeholder="Contraseña"
                                                />
                                            </div>
                                            <div class="form-terms">
                                                <div class="form-check mesm-2">
                                                    
                                                    <a
                                                        href="#"
                                                        class="btn btn-default forgot-pass"
                                                        >Recuperar contraseña</a
                                                    >
                                                </div>
                                            </div>
                                            <div class="form-button">
                                                <button
                                                    class="btn btn-primary"
                                                    type="submit"
                                                >
                                                    Iniciar sesión
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
