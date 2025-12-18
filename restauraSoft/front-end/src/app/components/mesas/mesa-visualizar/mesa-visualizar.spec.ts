import { ComponentFixture, TestBed } from '@angular/core/testing';

import { MesaVisualizar } from './mesa-visualizar';

describe('MesaVisualizar', () => {
  let component: MesaVisualizar;
  let fixture: ComponentFixture<MesaVisualizar>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [MesaVisualizar]
    })
    .compileComponents();

    fixture = TestBed.createComponent(MesaVisualizar);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
