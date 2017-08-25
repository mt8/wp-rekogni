=== WP Rekogni ===
Contributors: mt8.biz
Donate link: https://mt8.biz
Tags: tags,AWS,Amazon Rekognition
Requires at least: 4.8
Requires PHP: 7.0 or Higher
Tested up to: 4.8
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Assign Tags to Posts By Amazon Image Rekognition

== Description ==

Assign Tags to Posts By Amazon Image Rekognition

== Installation ==

1. Upload `wp-rekogni.zip` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== How to use ==

1. Setting your AWS IAM User information on page of "WP Rekogni"
2. Select the post you want to assign tags and perform bulk editing.

== AWS IAM Policy Example

```
{
    "Version": "2012-10-17",
    "Statement": [
        {
            "Effect": "Allow",
            "Action": [
                "rekognition:DetectLabels"
            ],
            "Resource": "*"
        }
    ]
}
```

== Filters ==

1. wp_rekogni_assign_setting : This filter can manage taxonomies that can be assigned to post types. The default is post and post_tag.
2. wp_rekogni_MaxLabels : This filter can change the number of Amazon Rekognition results.
3. wp_rekogni_MinConfidence : This filter is used to adjust the confidence of labels.

== Screenshots ==

1. Select the post you want to assign tags and perform bulk editing.

== Frequently Asked Questions ==

== Upgrade Notice ==

= 1.0.0 =
* First release

== Changelog ==

= 1.0.0 =
* First release
