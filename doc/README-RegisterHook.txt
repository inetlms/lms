# before -> przed
# after -> po

nodeinfo.php
		node_init_info

nodeadd.php
		node_add_before
		node_add_after
		node_add_init

voipaccountset.php
		voip_account_set_after

nodewarn.php
		node_warn_after

nodeset.php
		node_set_after

nodedel.php
		node_del_before
		node_del_after

nodeblockade.php
		node_blockade_after

nodeedit.php
		node_edit_before
		node_edit_after
		node_edit_init

networknodeinfo.php , sekcja interface, lista interfejsów bez węzła
		networknodeinfo_interface_addsql_field
		networknodeinfo_interface_addsql_join
		networknodeinfo_interface_addsql_where


LMS.class.php

    SendSMS ->
		-> send_sms_before


    customeradd -> 
		lms_customer_add_before
		lms_customer_add_after


    customerdel ->
		lms_customer_del_before
		lms_customer_del_after


    customerupdate -> 
		lms_customer_update_before
		lms_customer_update_after

    customerassignmentdelete -> 
		lms_customer_assignment_del_before
		lms_customer_assignment_del_after


    customerassignmentadd -> 
		lms_customer_assignment_add_before
		lms_customer_assignment_add_after


    nodeupdate -> 
		lms_node_update_before
		lms_node_update_after


    nodedelete -> 
		lms_node_del_before
		lms_node_del_after


    getnode -> 
		lms_getnode_after


    nodeadd -> 
		lms_node_add_before
		lms_node_add_after


    netdevlinknode -> 
		lms_netdevlinknode_before
		lms_netdevlinknode_after


    invoiceDelete -> 
		lms_invoice_del_before
		lms_invoice_del_after


    addbalance -> 
		lms_balance_add_before
		lms_balance_add_after


    delbalance -> 
		lms_balance_del_before
		lms_balance_del_after


    getnetdevlist -> 
		lms_netdev_list_after
		lms_netdev_list_sqladd_field
		lms_netdev_list_sqladd_join
		lms_netdev_list_sqladd_where


    getnetdev -> 
		lms_netdev_get_after
		lms_netdev_get_addsql_field
		lms_netdev_get_addsql_where
		lms_netdev_get_addsql_join


    netdevdellinks -> 
		lms_netdev_links_del_before
		lms_netdev_links_del_after

    DeleteNetDev -> 
		lms_netdev_del_before
		lms_netdev_del_after

    NetDevAdd -> 
		lms_netdev_add_before
		lms_netdev_add_after


    netDevUpdate -> 
		lms_netdev_update_before
		lms_netdev_update_after


    netdevlink -> 
		lms_netdev_links_add_before
		lms_netdev_links_add_after


    netdevUnLink -> 
		lms_netdev_unlink_before


    getQueue(helpdesk) -> 
		lms_getqueue_after
