
import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { LocationStrategy, HashLocationStrategy, DatePipe } from '@angular/common';
import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { APP_BASE_HREF } from '@angular/common';
import { HTTP_INTERCEPTORS, HttpClientModule } from '@angular/common/http';
import { environment } from 'src/environments/environment';
import { LogInComponent } from './log-in/log-in.component';
import { PopUpsModule } from 'src/pop-ups/pop-ups.module';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { ErrorInterceptor } from 'src/app/httpInterceptor';
import { MainComponent } from './main/main.component';
import { DirectivesModule } from 'src/directives/directives.module';
import { FilterComponent } from './filter/filter.component';

import * as $from from 'jquery';
import gsap from 'gsap';
import { Ruta1Component } from './main/ruta1/ruta1.component';
import { Ruta2Component } from './main/ruta2/ruta2.component';
import { Ruta3Component } from './main/ruta3/ruta3.component';
import { Ruta4Component } from './main/ruta4/ruta4.component';
import { HeaderComponent } from './header/header.component';
import { FooterComponent } from './footer/footer.component';


@NgModule({
    declarations: [
        AppComponent,
        LogInComponent,
        MainComponent,
        FilterComponent,
        Ruta1Component,
        Ruta2Component,
        Ruta3Component,
        Ruta4Component,
        HeaderComponent,
        FooterComponent

    ],
    imports: [
        BrowserModule,
        PopUpsModule,
        FormsModule,
        DirectivesModule,
        ReactiveFormsModule,
        AppRoutingModule,
        HttpClientModule
    ],
    exports: [
    ],
    providers: [
        DatePipe,
        { provide: LocationStrategy, useClass: HashLocationStrategy },
        { provide: APP_BASE_HREF, useValue: environment.apiUrl },
        [
            {
                provide: HTTP_INTERCEPTORS,
                useClass: ErrorInterceptor,
                multi: true
            }
        ]
    ],
    bootstrap: [AppComponent]
})
export class AppModule { }
