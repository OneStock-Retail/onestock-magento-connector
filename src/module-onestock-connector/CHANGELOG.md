# Changelog

All notable changes to this project will be documented in this file.

## 1.0.0 - 2024-08-24

- Initial release

Primary features :
- Daily cron onestock_full_stock_import to import stock from csv file
- Observe quote submit to export order to Onestock
- Webhook /rest/V1/order/:orderId/onestock_updates to receive updates, such as a shipments, of this order
- Configurable settings in etc/adminhtml/system.xml

Secondary features :
- Periodic cron onestock_diff_stock_import.
- Sweeper-car cron to manage export error.
- Dummy webhook  /rest/V1/order/:orderId/onestock_export for internal async use.
- Helper using fieldset to convert a magento order into an order in onestock rest api.
- Login helper
- Config helper
- Virtual Type to share code between daily stock import.
- Guzzle requests toward onestock rest api in order to login, fetch and post order

Minor customisation :
- Default config
- Translation
- Adminhtml acl
- Crons in custom specific cron group
- Extra onestock_id sql columns on shipment and creditmemo, flag and counter on order.
- Plugin add_tracking_link_to_shipment to save tracking link alongside shipment
- Custom log file var/log/onestock.log