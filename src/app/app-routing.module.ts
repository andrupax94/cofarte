import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { authGuard } from '../guards/auth.guard';
import { procesing } from '../guards/procesing.guard';
import { LogInComponent } from './log-in/log-in.component';
import { MainComponent } from './main/main.component';
import { Ruta1Component } from './main/ruta1/ruta1.component';
import { Ruta2Component } from './main/ruta2/ruta2.component';
import { Ruta3Component } from './main/ruta3/ruta3.component';
import { Ruta4Component } from './main/ruta4/ruta4.component';


const routes: Routes = [
    { path: '', component: Ruta1Component, canActivate: [authGuard] },
    { path: 'ruta1', component: Ruta1Component, canActivate: [authGuard] },
    { path: 'ruta2', component: Ruta2Component, canActivate: [authGuard] },
    { path: 'ruta3', component: Ruta3Component, canActivate: [authGuard] },
    { path: 'ruta4', component: Ruta4Component, canActivate: [authGuard] },
    { path: 'logIn', component: LogInComponent, canActivate: [procesing] },
    // Puedes agregar más rutas según sea necesario
];

@NgModule({
    imports: [RouterModule.forRoot(routes)],
    exports: [RouterModule]
})
export class AppRoutingModule {
}
