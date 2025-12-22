import { ComponentFixture, TestBed } from '@angular/core/testing';

import { MesaSalvar } from './mesa-salvar';

describe('MesaSalvar', () => {
  let component: MesaSalvar;
  let fixture: ComponentFixture<MesaSalvar>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [MesaSalvar]
    })
    .compileComponents();

    fixture = TestBed.createComponent(MesaSalvar);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
