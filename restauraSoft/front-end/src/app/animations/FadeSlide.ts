import { animate, style, transition, trigger } from "@angular/animations";

export const fadeSlide = trigger('fadeSlide', [
  transition(':enter', [
    style({ opacity: 0 }),
    animate('200ms ease-in', style({ opacity: 1 })),
  ])
])
