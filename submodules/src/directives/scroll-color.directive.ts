import { Directive, ElementRef, Input } from '@angular/core';

@Directive({
    selector: '[scrollColor]'
})
export class ScrollColorDirective {

    @Input('scrollColor') scrollColor: string;

    constructor(private el: ElementRef) {
        this.scrollColor = 'gray';
    }

    ngOnInit() {
        this.el.nativeElement.style.overflowY = 'auto';
        this.el.nativeElement.style.scrollbarColor = this.scrollColor + ' transparent';
    }
}
