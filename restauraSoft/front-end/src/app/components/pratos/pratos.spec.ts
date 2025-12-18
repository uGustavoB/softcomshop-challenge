import { ComponentFixture, TestBed } from '@angular/core/testing';

import { Pratos } from './pratos';

describe('Pratos', () => {
  let component: Pratos;
  let fixture: ComponentFixture<Pratos>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [Pratos]
    })
    .compileComponents();

    fixture = TestBed.createComponent(Pratos);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
