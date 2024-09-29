import { Injectable } from '@angular/core';
import { BehaviorSubject } from 'rxjs';
type typeModal = 'question' | 'alert' | 'disclaimer';
@Injectable({
    providedIn: 'root'
})

export class ModalesService {

    constructor() {
        this.activo = false;
        this.question.aceptar = () => {
            this.cerrar(true);
            this.callback();
        }
        this.question.activo = false;
        this.question.h3 = 'Desea Continuar?';
        this.question.info = 'Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum haum.';
        this.typeModal = 'alert';
    }
    private typeModalOB = new BehaviorSubject<any>(null);
    SDtypeModalOB = this.typeModalOB.asObservable();
    SettypeModalOB(nuevosDatos: any) {
        this.typeModal = nuevosDatos;
        this.typeModalOB.next(nuevosDatos);
    }
    private disclaimer: string = `

        <p>Las páginas web presentadas en este portafolio son recreaciones a simple vista de las homepages de sitios web preexistentes. Se ha desarrollado un código propio para replicar la apariencia visual de dichas páginas, sin embargo, es importante destacar que el código subyacente es completamente diferente y no se ha utilizado ninguna parte del código original de las páginas referenciadas.</p>
        <p>Es crucial enfatizar que estas recreaciones se limitan únicamente a la página de inicio (homepage) de los sitios web mencionados y no abarcan la totalidad de sus funcionalidades ni características.</p>
        <p>Además, declaro explícitamente que no he participado ni formado parte del proceso de desarrollo de las páginas web originales que han servido como inspiración para estas recreaciones.</p>
        <p>Estos proyectos se han creado con fines estrictamente educativos y formativos, con el objetivo de aprender y practicar técnicas de diseño y desarrollo web.</p>
    `;


    public typeModal: typeModal;
    public activo: boolean;
    public question: any = [];
    private callback() {
        console.log('Sin Callback');
    }
    private callback2() {
        console.log('Sin Callback');
    }
    public cerrar(callB: boolean = false) {
        $('#modal').fadeOut(200, () => {
            this.activo = false;
            if (!callB)
                this.callback2();
            this.question.activo = false;
        });
    }
    public open(typeModal: typeModal = 'alert', h3?: string, info?: string, callback?: VoidFunction, callback2?: VoidFunction) {
        this.question.h3 = h3;
        this.question.info = info;
        switch (typeModal) {
            case 'disclaimer':
                this.question.info = this.disclaimer;
                this.question.h3 = 'descargo de responsabilidad';
                break;
            case 'alert':
                break;
            case 'question':
                if (callback !== undefined) {
                    this.callback = callback;

                    if (callback2 !== undefined) {
                        this.callback2 = callback2;
                    }

                }
                break;
        }
        this.SettypeModalOB(typeModal);
        this.activo = true;
        this.question.activo = true;
        $('#modal').fadeIn(200);

    }
}
