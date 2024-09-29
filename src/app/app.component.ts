import { Component, OnInit, ViewEncapsulation } from '@angular/core';
import { SessionService } from '../factory/session.service';
import { CargaService } from 'src/factory/carga.service';
import { NavigationEnd, Router } from '@angular/router';
import { authGuard } from '../guards/auth.guard';
import { filter } from 'rxjs/operators';
@Component({
    selector: 'app-root',
    templateUrl: './app.component.html',
    styleUrls: ['./app.component.css'],
    // encapsulation: ViewEncapsulation.None
})
export class AppComponent {
    title = 'cofarte';
    public currentPath = '';
    public user: any;
    public pageFilter: string = "none";
    constructor(
        private auth: authGuard,
        private session: SessionService,
        private carga: CargaService,
        private router: Router,

    ) {

        this.user = { state: false, data: [], mensaje: '' };
        this.carga.to('body');
        this.currentPath = this.router.url;
        this.carga.play();
    }
    reload() {
        this.auth.reload();
    }

    changePage(e: MouseEvent) {
        const targetElement = e.currentTarget as HTMLElement;
        const pageToChange = targetElement.getAttribute('routerLink') as string;

        if (this.currentRoute() !== pageToChange) {
            this.carga.to('body');
            this.carga.play();
        }
    }
    currentRoute() {
        const segments = this.router.url.split('/');
        return '/' + segments[segments.length - 1];
    }



    cargaPause() {
        setTimeout(() => {
            this.carga.pause();
        }, 200);
    }
    ngOnInit() {
        this.session.SDuserCookie$.subscribe((data) => {
            if (data !== null) {
                this.user = data;
            }
        });
        this.router.events.subscribe(event => {
            if (event instanceof NavigationEnd) {
                this.cargaPause();
            }
        });
    }
}
