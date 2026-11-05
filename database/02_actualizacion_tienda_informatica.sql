-- ============================================================
-- tienda_informatica - Script de actualización (ejecutar DESPUÉS de
-- importar 01_BD_tienda_informatica.sql en phpMyAdmin)
-- Adapta la BD al backlog: roles, stock, estados, entrega.
-- ============================================================
USE `tienda_informatica`;

-- 1) Roles según RF08 (se migra el rol 'Usuario' a 'Cliente')
ALTER TABLE `usuarios` MODIFY `rol`
  ENUM('SuperAdmin','Administrador','Vendedor','Almacenero','Repartidor','Cliente','Usuario') NOT NULL DEFAULT 'Cliente';
UPDATE `usuarios` SET `rol`='Cliente' WHERE `rol`='Usuario';
ALTER TABLE `usuarios` MODIFY `rol`
  ENUM('SuperAdmin','Administrador','Vendedor','Almacenero','Repartidor','Cliente') NOT NULL DEFAULT 'Cliente';

-- 2) Productos: descripción larga (RF04), stock y "A Pedido" (RF04, HU03, HU04)
ALTER TABLE `productos`
  ADD COLUMN `descripcion_larga` TEXT NULL AFTER `descripcion`,
  ADD COLUMN `stock` INT NOT NULL DEFAULT 0 AFTER `precio`,
  ADD COLUMN `a_pedido` TINYINT(1) NOT NULL DEFAULT 0 AFTER `stock`;

-- 3) Ventas: fecha y hora de entrega (HU06)
ALTER TABLE `ventas` ADD COLUMN `fecha_entrega` DATETIME NULL AFTER `metodo_pago`;

-- 4) Datos de ejemplo: stock para los 3 productos existentes
UPDATE `productos` SET `stock`=25, `descripcion_larga`='Entrada 100-240V AC, salida 5V/3A, 9V/2.2A. Carga rápida PD de 20W. Compatible con iPhone y Android.' WHERE `id`=1;
UPDATE `productos` SET `stock`=3,  `descripcion_larga`='USB 3.0, velocidad de lectura de hasta 100 MB/s. Compatible con Windows, macOS y Linux.' WHERE `id`=2;
UPDATE `productos` SET `stock`=0, `a_pedido`=1, `descripcion_larga`='DDR4 SODIMM 3200 MHz, 1.2V. Para laptops. Se trae a pedido (consultar tiempo de entrega).' WHERE `id`=3;

-- 5) Productos adicionales de ejemplo
INSERT INTO `productos` (`categoria_id`,`marca_id`,`nombre`,`descripcion`,`descripcion_larga`,`precio`,`stock`,`a_pedido`,`activo`) VALUES
(2,4,'Disco Duro Externo 1TB','Disco portátil USB 3.0','USB 3.0, 5400 RPM, alimentación por USB, compatible con Windows y macOS.',230.00,12,0,1),
(4,5,'Adaptador de Red USB TP-Link','Adaptador WiFi USB 300Mbps','Estándar 802.11n, hasta 300 Mbps, USB 2.0.',55.00,8,0,1),
(4,1,'Case para Disco 2.5"','Case USB 3.0 para HDD/SSD SATA','Soporta discos SATA de 2.5", USB 3.0, sin herramientas.',40.00,4,0,1),
(1,3,'Cable USB-C a USB-C 1m','Cable de carga y datos','Soporta 60W de carga, trenzado de nailon, 1 metro.',29.00,0,0,1);

-- 6) Usuarios de prueba (contraseñas reales con password_hash / bcrypt)
--    Admin123*   -> admin@tienda.com y superadmin@tienda.com
--    Cliente123* -> cliente@correo.com
--    Staff123*   -> vendedor / almacenero / repartidor @tienda.com
UPDATE `usuarios` SET `password`='$2y$12$WCUeoOY07zWm8C.Lb9vEC.j/W6NvLkigWYBBkRiBvmA147iuTlwKq' WHERE `id`=1;
UPDATE `usuarios` SET `password`='$2y$12$aK1kDa3HbvrH25vaIBJFNuPQoiYRIl5XPvWnCq.YS1KhZgKszrogG' WHERE `id`=2;
INSERT INTO `usuarios` (`nombre`,`email`,`password`,`rol`,`activo`) VALUES
('Super Admin','superadmin@tienda.com','$2y$12$WCUeoOY07zWm8C.Lb9vEC.j/W6NvLkigWYBBkRiBvmA147iuTlwKq','SuperAdmin',1),
('Vendedor Demo','vendedor@tienda.com','$2y$12$MLrv2jOWkCi4RME4Rs9RS.1648bAx7dxx8x8VnCUAyCp6fudYUel.','Vendedor',1),
('Almacenero Demo','almacenero@tienda.com','$2y$12$MLrv2jOWkCi4RME4Rs9RS.1648bAx7dxx8x8VnCUAyCp6fudYUel.','Almacenero',1),
('Repartidor Demo','repartidor@tienda.com','$2y$12$MLrv2jOWkCi4RME4Rs9RS.1648bAx7dxx8x8VnCUAyCp6fudYUel.','Repartidor',1);
