import { animate, style, transition, trigger } from "@angular/animations";

export const fadeSlide = trigger('fadeSlide', [
  transition(':enter', [
    style({ opacity: 0 }),
    animate('800ms ease-in', style({ opacity: 1 })),
  ])
])
