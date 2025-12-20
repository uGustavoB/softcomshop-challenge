import { ComponentFixture, TestBed } from '@angular/core/testing';

import { PedidoDetalhes } from './pedido-detalhes';

describe('PedidoDetalhes', () => {
  let component: PedidoDetalhes;
  let fixture: ComponentFixture<PedidoDetalhes>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [PedidoDetalhes]
    })
    .compileComponents();

    fixture = TestBed.createComponent(PedidoDetalhes);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
