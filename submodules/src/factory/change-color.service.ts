import { Injectable, Renderer2, RendererFactory2 } from '@angular/core';

@Injectable({
    providedIn: 'root'
})
export class ChangeColorService {

    public primary: string;
    public secondary: string;
    public tertiary: string;
    public quaternary: string;
    public quinary: string;
    public senary: string;
    public root: any;
    public changeSW: boolean;
    private renderer: Renderer2;

    constructor(private rendererFactory: RendererFactory2) {
        this.renderer = this.rendererFactory.createRenderer(null, null);
        this.primary = '#005493';
        this.secondary = '#4691db';
        this.tertiary = '#176ab6';
        this.quaternary = '#32465E';
        this.quinary = '#000814';
        this.senary = '#000';

        this.changeSW = false;
        this.root = document.querySelector(':root');
    }

    ngOnInit() {
        this.root = document.querySelector(':root');
        const styles = getComputedStyle(this.root as Element);
        this.primary = styles.getPropertyValue('--primary').trim();
        this.secondary = styles.getPropertyValue('--secondary').trim();
        this.tertiary = styles.getPropertyValue('--tertiary').trim();
        this.quaternary = styles.getPropertyValue('--quaternary').trim();
        this.quinary = styles.getPropertyValue('--quinary').trim();
        this.senary = styles.getPropertyValue('--senary').trim();
    }

    changeColor(primary: string = this.primary, secondary: string = this.secondary, tertiary: string = this.tertiary, quaternary: string = this.quaternary, quinary: string = this.quinary, senary: string = this.senary) {
        this.primary = primary;
        this.secondary = secondary;
        this.tertiary = tertiary;
        this.quaternary = quaternary;
        this.quinary = quinary;
        this.senary = senary;

        this.renderer.setStyle(this.root, '--primary', this.primary);
        this.renderer.setStyle(this.root, '--secondary', this.secondary);
        this.renderer.setStyle(this.root, '--tertiary', this.tertiary);
        this.renderer.setStyle(this.root, '--quaternary', this.quaternary);
        this.renderer.setStyle(this.root, '--quinary', this.quinary);
        this.renderer.setStyle(this.root, '--senary', this.senary);
        this.changeSW = true;
    }




}
