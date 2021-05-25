<template>
    <div>
        <shop-header-component></shop-header-component>
        <div class="content pt-0">
            <div class="page-wrapper">
                <div class="page-body mt-0">

                    <div class="container-xl">
                        <div class="row">
                            <div class="col-12">
                                <form>
                                    <div class="form-group mb-3">
                                        <label class="form-label">Nombre completo</label>
                                        <div>
                                            <input type="text" class="form-control" aria-describedby="emailHelp" placeholder="Enter email">
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label">Email</label>
                                        <div>
                                            <input type="email" class="form-control" aria-describedby="emailHelp" placeholder="Enter email">
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label">Contacto (Celular o Teléfono)</label>
                                        <div>
                                            <input type="text" class="form-control" aria-describedby="emailHelp" placeholder="(xxx)-xxx-xxxx">
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label">Identificación (Cédula, RNC o # Pasaporte) </label>
                                        <div>
                                            <input type="text" class="form-control" aria-describedby="emailHelp" placeholder="">
                                        </div>
                                    </div>
                                    <div class="form-footer">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
}
</script>
