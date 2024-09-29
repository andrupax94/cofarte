import { Component, ViewEncapsulation } from '@angular/core';
import { FilterService } from '../filter/filter.service';
import { SessionService } from 'src/factory/session.service';
import { CargaService } from 'src/factory/carga.service';
import { NavigationEnd, Router } from '@angular/router';

@Component({
    selector: 'app-header',
    templateUrl: './header.component.html',
    styleUrls: ['./header.component.css'],
    encapsulation: ViewEncapsulation.None
})
export class HeaderComponent {
    public abrirCerrarFiltro;
    public user: any;
    public bgColor = 'grey';
    public currentPath = '';
    public headerSW = true;
    public headerTop = 0;
    public headerH = 125;
    constructor(
        private filtro: FilterService,
        private session: SessionService,
        private carga: CargaService,
        private router: Router,

    ) {
        if (!this.headerSW) {
            this.headerTop = -this.headerH;
        }
        this.user = { state: false, data: [], mensaje: '' };
        this.abrirCerrarFiltro = filtro.abrirCerrarFiltro;
    }
    closeTopMenu(e: MouseEvent) {
        const targetElement = e.currentTarget as HTMLElement;
        const topVar = $(targetElement).parent();
        let top = ($(topVar).css('top') !== undefined) ? parseInt($(topVar).css('top')) : 0;
        if (top < -1) {
            this.headerTop = 0;
        }
        else {

            this.headerTop = -this.headerH;

        }
        this.headerSW = !this.headerSW;
    }
    animarMenu(e: MouseEvent) {
        const targetElement = $(e.currentTarget as HTMLElement).find("ul").eq(0);
        if (targetElement.length > 0) {
            targetElement.css("display", "block");
            setTimeout(() => {
                targetElement.css("opacity", "1");
                targetElement.css("top", "32px");
            }, 10);

        }

    }
    animarMenu2(e: MouseEvent) {
        const targetElement = $(e.currentTarget as HTMLElement);
        const targetElement2 = $(e.currentTarget as HTMLElement).find("ul").eq(0);

        if (targetElement2.length > 0) {
            // Obtener las coordenadas y dimensiones del elemento
            const boundingRect = targetElement[0].getBoundingClientRect();

            // Coordenadas del mouse al salir
            const mouseX = e.clientX;
            const mouseY = e.clientY;

            // Definir un margen de 10px
            const margin = 10;

            // Detectar si el mouse salió por el lado derecho, izquierdo, o superior con un margen de 10px
            const outOnSidesOrTop =
                mouseX < boundingRect.left - margin || // Salió por la izquierda con margen
                mouseX > boundingRect.right + margin || // Salió por la derecha con margen
                mouseY < boundingRect.top - margin; // Salió por arriba con margen

            // Detectar si el mouse salió por debajo sin margen
            const outFromBottom = mouseY > boundingRect.bottom && mouseY > boundingRect.bottom + 300;

            if (outFromBottom) {
                console.log('El mouse salió por debajo del elemento');
            } else {
                // Animación si el mouse salió por los lados o arriba
                targetElement2.css("opacity", "0");
                targetElement2.css("top", "0px");
                setTimeout(() => {
                    targetElement2.css("display", "none");
                }, 100);
                console.log('El mouse salió por arriba o por los lados del elemento');
            }
        }
    }
    animarMenu3(e: MouseEvent) {
        const targetElement2 = $(e.currentTarget as HTMLElement);
        const targetElement = $(e.currentTarget as HTMLElement).parent('button').eq(0);

        if (targetElement2.length > 0) {
            // Obtener las coordenadas y dimensiones del elemento
            const boundingRect = targetElement[0].getBoundingClientRect();

            // Coordenadas del mouse al salir
            const mouseX = e.clientX;
            const mouseY = e.clientY;

            // Definir un margen de 10px
            const margin = 10;

            // Detectar si el mouse salió por el lado derecho, izquierdo, o superior con un margen de 10px
            const outOnSidesOrTop =
                mouseX < boundingRect.left - margin || // Salió por la izquierda con margen
                mouseX > boundingRect.right + margin || // Salió por la derecha con margen
                mouseY < boundingRect.top - margin; // Salió por arriba con margen

            // Detectar si el mouse salió por debajo sin margen
            const outFromTop = mouseY < boundingRect.top;

            if (outFromTop) {
                targetElement2.css("opacity", "0");
                targetElement2.css("top", "0px");
                setTimeout(() => {
                    targetElement2.css("display", "none");
                }, 100);
                console.log('El mouse salió por arriba ');
            }
        }
    }

    changePage(e: MouseEvent) {
        const targetElement = e.currentTarget as HTMLElement;
        const pageToChange = targetElement.getAttribute('routerLink') as string;

        if (this.currentRoute() !== pageToChange) {
            this.carga.to('body');
            this.carga.play();
        }

    }
    logOut() {
        this.carga.to('body');
        this.carga.changeInfo(undefined, 'Cerrando Sesion');
        this.carga.play();
        this.session.logOut();
        setTimeout(() => {
            this.router.navigate(['logIn']);
        }, 500);
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
