
import { Injectable } from '@angular/core';
import { BehaviorSubject } from 'rxjs';
import { DatePipe } from '@angular/common';

@Injectable({
    providedIn: 'root'
})
export class FilterService {
    private page = new BehaviorSubject<any>(null);
    pageSD$ = this.page.asObservable();
    private filtros = new BehaviorSubject<any>(null);
    filtrosSD$ = this.filtros.asObservable();

    filtroOpen = false;
    filtroWidth: number | undefined = 0;
    order: 'asc' | 'desc' = 'asc';
    orderBy: string = 'fechaLimite';
    dateStart: string | null = this.datePipe.transform(new Date('11-02-1999'), 'yyyy-MM-dd');
    dateEnd: string | null = this.datePipe.transform(new Date('11-02-2999'), 'yyyy-MM-dd');
    onlyFilter: string = 'true';
    perPage: number = 5;

    compartirPagina(pagina: any) {
        this.page.next(pagina);
    }
    compartirFiltros(filtros: any, typeOp: string) {
        let data = {
            filtros: filtros, typeOp: typeOp
        }
        this.filtros.next(data);
    }
    abrirCerrarFiltro() {
        let left = ($('#filtros_s_cont').css('left') !== undefined) ? parseInt($('#filtros_s_cont').css('left')) : 0;
        if (left > -10) {
            this.filtroWidth = $('#filtros_s_cont').width();
            $('#filtros_s_cont').css('left', '-' + this.filtroWidth + 'px');

        }
        else {

            $('#filtros_s_cont').css('left', '0px');

        }
        this.filtroOpen = !this.filtroOpen;
    }
    constructor(private datePipe: DatePipe) {
        if (this.filtroOpen) {
            setTimeout(() => {
                this.abrirCerrarFiltro();
            }, 500);
        }
        else {
            this.filtroOpen = !this.filtroOpen;
        }
    }
}
